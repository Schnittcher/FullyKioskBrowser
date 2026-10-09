<?php

declare(strict_types=1);

require_once __DIR__ . '/libs/FKBApiClient.php';
require_once __DIR__ . '/libs/VariableDefinitions.php';

class FKBDevice extends IPSModuleStrict
{
    private const STATUS_CONNECTION_ERROR = 201;

    // Läuft ein MQTT-Parent, wird nur noch selten per REST abgefragt (Sicherheitsnetz)
    private const MQTT_FALLBACK_INTERVAL = 300;

    public function Create(): void
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 2323);
        $this->RegisterPropertyString('Password', '');
        $this->RegisterPropertyBoolean('UseHTTPS', false);
        $this->RegisterPropertyInteger('Updateinterval', 10);
        $this->RegisterPropertyString('MQTTDeviceId', '');
        $this->RegisterPropertyString('Apps', '[]');
        $this->RegisterPropertyString('Variables', '[]');

        $this->RegisterTimer('FKB_Update', 0, 'FKB_getDeviceInfo($_IPS[\'TARGET\']);');
    }

    public function Destroy(): void
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function GetConfigurationForm(): string
    {
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);
        $active = $this->getActiveIdents();

        $values = [];
        foreach (FKBVariableDefinitions::all(fn (string $Text): string => $this->Translate($Text), []) as $ident => $definition) {
            $values[] = [
                'Ident'    => $ident,
                'Variable' => $this->Translate($definition['Caption']),
                'Active'   => $active[$ident] ?? true
            ];
        }
        foreach ($form['elements'] as &$element) {
            if (($element['name'] ?? '') === 'Variables') {
                $element['values'] = $values;
            }
        }
        unset($element);

        return json_encode($form);
    }

    public function ApplyChanges(): void
    {
        //Never delete this line!
        parent::ApplyChanges();

        // Migration: Der Ident hieß früher "locationProvide" (Tippfehler, die Variable wurde nie befüllt)
        $oldId = @IPS_GetObjectIDByIdent('locationProvide', $this->InstanceID);
        if ($oldId !== false && @IPS_GetObjectIDByIdent('locationProvider', $this->InstanceID) === false) {
            IPS_SetIdent($oldId, 'locationProvider');
        }

        $active = $this->getActiveIdents();
        $position = 0;
        foreach ($this->getDefinitions() as $ident => $definition) {
            $isActive = $active[$ident] ?? true;
            $this->MaintainVariable($ident, $this->Translate($definition['Caption']), $definition['Type'], $definition['Presentation'], ++$position, $isActive);
            if ($isActive) {
                // Bei einer deaktivierten Variable gibt es nichts zu pflegen, sie wurde oben entfernt
                $this->MaintainAction($ident, $definition['Action']);
            }
        }

        // MQTT: nur Nachrichten des eigenen Tablets annehmen (Topic enthält die Geräte-ID)
        $deviceId = trim($this->ReadPropertyString('MQTTDeviceId'));
        if ($deviceId !== '') {
            $this->SetReceiveDataFilter('.*' . preg_quote($deviceId, '/') . '.*');
        } else {
            $this->SetReceiveDataFilter('.*(deviceInfo|event).*');
        }

        if (trim($this->ReadPropertyString('Host')) === '') {
            $this->SetTimerInterval('FKB_Update', 0);
            $this->SetStatus(IS_INACTIVE);
            return;
        }

        $this->SetStatus(IS_ACTIVE);
        $interval = max(1, $this->ReadPropertyInteger('Updateinterval'));
        if ($this->HasActiveParent()) {
            $interval = max($interval, self::MQTT_FALLBACK_INTERVAL);
        }
        $this->SetTimerInterval('FKB_Update', $interval * 1000);
    }

    public function ReceiveData(string $JSONString): string
    {
        $data = json_decode($JSONString, true);
        if (!is_array($data) || !isset($data['Topic'])) {
            return '';
        }
        $this->SendDebug('MQTT Topic', (string) $data['Topic'], 0);

        // IPSModuleStrict: Der Payload kommt HEX-kodiert
        $hex = (string) ($data['Payload'] ?? '');
        if ($hex === '' || strlen($hex) % 2 !== 0 || !ctype_xdigit($hex)) {
            return '';
        }
        $payload = hex2bin($hex);

        $parts = explode('/', (string) $data['Topic']);
        $count = count($parts);
        $deviceId = trim($this->ReadPropertyString('MQTTDeviceId'));
        if ($deviceId !== '' && $parts[$count - 1] !== $deviceId) {
            return '';
        }

        if ($count >= 2 && $parts[$count - 2] === 'deviceInfo') {
            $info = json_decode($payload, true);
            if (is_array($info)) {
                $this->updateFromDeviceInfo($info);
            }
        } elseif ($count >= 3 && $parts[$count - 3] === 'event') {
            $details = json_decode($payload, true);
            $this->storeEvent($parts[$count - 2], is_array($details) ? $details : []);
        }
        return '';
    }

    public function RequestAction(string $Ident, mixed $Value): void
    {
        switch ($Ident) {
            case 'screenOn':
                $this->screen((bool) $Value);
                break;
            case 'textToSpeech':
                $this->textToSpeech((string) $Value);
                break;
            case 'isInScreensaver':
                $this->screensaver((bool) $Value);
                break;
            case 'isInDaydream':
                $this->daydream((bool) $Value);
                break;
            case 'kioskMode':
                $this->kioskMode((bool) $Value);
                break;
            case 'toForeground':
                $this->toForeground();
                break;
            case 'toBackground':
                $this->toBackground();
                break;
            case 'Apps':
                $apps = json_decode($this->ReadPropertyString('Apps'), true);
                $app = is_array($apps) ? ($apps[((int) $Value) - 1] ?? null) : null;
                if ($app === null) {
                    $this->LogMessage($this->Translate('App not found'), KL_WARNING);
                    break;
                }
                $this->startApplication((string) $app['Package']);
                break;
            case 'maintenanceMode':
                $this->maintenanceMode((bool) $Value);
                break;
            case 'setOverlayMessage':
                $this->setOverlayMessage((string) $Value);
                break;
            case 'loadStartURL':
                $this->loadStartURL();
                break;
            case 'loadURL':
                $this->loadURL((string) $Value);
                break;
            case 'screenBrightness':
                if ($this->setStringSetting('screenBrightness', (string) max(0, min(255, (int) $Value)))) {
                    $this->SetValue($Ident, max(0, min(255, (int) $Value)));
                }
                break;
            case 'volumeMusic':
                // Musik ist der Audio-Stream 3
                $level = max(0, min(100, (int) $Value));
                if ($this->setAudioVolume($level, 3)) {
                    $this->SetValue($Ident, $level);
                }
                break;
            case 'DeviceControl':
                switch ((int) $Value) {
                    case 0:
                        $this->shutdownDevice();
                        break;
                    case 1:
                        $this->rebootDevice();
                        break;
                    default:
                        throw new Exception('Invalid Value');
                }
                break;
            default:
                throw new Exception('Invalid Ident');
        }
    }

    // ---- Anzeige und Energie ----

    public function screen(bool $Value): bool
    {
        if ($this->command($Value ? 'screenOn' : 'screenOff')) {
            $this->setValueIfExists('screenOn', $Value);
            return true;
        }
        return false;
    }

    public function screensaver(bool $Value): bool
    {
        if ($this->command($Value ? 'startScreensaver' : 'stopScreensaver')) {
            $this->setValueIfExists('isInScreensaver', $Value);
            return true;
        }
        return false;
    }

    public function daydream(bool $Value): bool
    {
        if ($this->command($Value ? 'startDaydream' : 'stopDaydream')) {
            $this->setValueIfExists('isInDaydream', $Value);
            return true;
        }
        return false;
    }

    public function forceSleep(): bool
    {
        return $this->command('forceSleep');
    }

    public function triggerMotion(): bool
    {
        return $this->command('triggerMotion');
    }

    // ---- Kiosk und Apps ----

    public function kioskMode(bool $Value): bool
    {
        if ($this->command($Value ? 'lockKiosk' : 'unlockKiosk')) {
            $this->setValueIfExists('kioskMode', $Value);
            return true;
        }
        return false;
    }

    public function maintenanceMode(bool $Value): bool
    {
        if ($this->command($Value ? 'enableLockedMode' : 'disableLockedMode')) {
            $this->setValueIfExists('maintenanceMode', $Value);
            return true;
        }
        return false;
    }

    public function startApplication(string $Value): bool
    {
        return $this->command('startApplication', ['package' => $Value]);
    }

    public function toForeground(): bool
    {
        return $this->command('toForeground');
    }

    public function toBackground(): bool
    {
        return $this->command('toBackground');
    }

    public function popFragment(): bool
    {
        return $this->command('popFragment');
    }

    public function restartApp(): bool
    {
        return $this->command('restartApp');
    }

    public function exitApp(): bool
    {
        return $this->command('exitApp');
    }

    public function loadApkFile(string $Value): bool
    {
        return $this->command('loadApkFile', ['url' => $Value]);
    }

    public function setOverlayMessage(string $Value): bool
    {
        return $this->command('setOverlayMessage', ['text' => $Value]);
    }

    public function shutdownDevice(): bool
    {
        return $this->command('shutdownDevice');
    }

    public function rebootDevice(): bool
    {
        return $this->command('rebootDevice');
    }

    // ---- Browser ----

    public function loadStartURL(): bool
    {
        return $this->command('loadStartURL');
    }

    public function loadURL(string $Value): bool
    {
        return $this->command('loadURL', ['url' => $Value]);
    }

    public function injectJavascript(string $Code): bool
    {
        return $this->command('injectJavascript', ['code' => $Code]);
    }

    public function refreshTab(): bool
    {
        return $this->command('refreshTab');
    }

    public function focusTab(int $Tab): bool
    {
        return $this->command('focusTab', ['tab' => $Tab]);
    }

    public function closeTab(int $Tab): bool
    {
        return $this->command('closeTab', ['tab' => $Tab]);
    }

    public function clearCache(): bool
    {
        return $this->command('clearCache');
    }

    public function clearWebstorage(): bool
    {
        return $this->command('clearWebstorage');
    }

    public function clearCookies(): bool
    {
        return $this->command('clearCookies');
    }

    public function resetWebview(): bool
    {
        return $this->command('resetWebview');
    }

    // ---- Sprache, Ton und Video ----

    public function textToSpeech(string $Value): bool
    {
        return $this->command('textToSpeech', ['text' => $Value]);
    }

    public function textToSpeechEx(string $Value, string $Locale, bool $Queue): bool
    {
        $parameters = ['text' => $Value, 'queue' => (int) $Queue];
        if ($Locale !== '') {
            $parameters['locale'] = $Locale;
        }
        return $this->command('textToSpeech', $parameters);
    }

    public function stopTextToSpeech(): bool
    {
        return $this->command('stopTextToSpeech');
    }

    public function setAudioVolume(int $level, int $stream): bool
    {
        return $this->command('setAudioVolume', ['level' => $level, 'stream' => $stream]);
    }

    public function playSound(string $url, bool $loop, int $stream): bool
    {
        return $this->command('playSound', ['url' => $url, 'loop' => $loop, 'stream' => $stream]);
    }

    public function stopSound(): bool
    {
        return $this->command('stopSound');
    }

    public function playVideo(string $url, bool $loop, bool $showControls, bool $exitOnTouch, bool $exitOnCompletion): bool
    {
        return $this->command('playVideo', [
            'url'              => $url,
            'loop'             => (int) $loop,
            'showControls'     => (int) $showControls,
            'exitOnTouch'      => (int) $exitOnTouch,
            'exitOnCompletion' => (int) $exitOnCompletion
        ]);
    }

    public function stopVideo(): bool
    {
        return $this->command('stopVideo');
    }

    // ---- Einstellungen ----

    public function setBooleanSetting(string $Key, bool $Value): bool
    {
        return $this->command('setBooleanSetting', ['key' => $Key, 'value' => $Value]);
    }

    public function setStringSetting(string $Key, string $Value): bool
    {
        return $this->command('setStringSetting', ['key' => $Key, 'value' => $Value]);
    }

    /**
     * Liefert alle Einstellungen des Tablets als JSON.
     */
    public function listSettings(): string
    {
        $client = $this->createClient();
        $result = $client->request('listSettings');
        if (!is_array($result)) {
            $this->LogMessage(sprintf($this->Translate('Command %s failed: %s'), 'listSettings', $client->getLastError()), KL_WARNING);
            return '';
        }
        return json_encode($result);
    }

    // ---- Bilder ----

    /**
     * Speichert einen Screenshot als Medienobjekt "Screenshot" unterhalb der Instanz.
     */
    public function getScreenshot(): bool
    {
        return $this->storeImage('getScreenshot', 'Screenshot', 'png');
    }

    /**
     * Speichert ein Kamerabild als Medienobjekt "Camshot" unterhalb der Instanz (Bewegungserkennung muss aktiv sein).
     */
    public function getCamshot(): bool
    {
        return $this->storeImage('getCamshot', 'Camshot', 'jpg');
    }

    // ---- Zustand ----

    /**
     * Fragt die Geräteinformationen per REST ab und schreibt sie in die Variablen.
     */
    public function getDeviceInfo(): bool
    {
        if (trim($this->ReadPropertyString('Host')) === '') {
            return false;
        }
        $client = $this->createClient();
        $result = $client->request('getDeviceInfo');
        if (!is_array($result)) {
            $this->SendDebug('No Data', $client->getLastError(), 0);
            if ($this->GetStatus() !== self::STATUS_CONNECTION_ERROR) {
                $this->SetStatus(self::STATUS_CONNECTION_ERROR);
                $this->LogMessage(sprintf($this->Translate('Tablet not reachable: %s'), $client->getLastError()), KL_WARNING);
            }
            return false;
        }
        if ($this->GetStatus() !== IS_ACTIVE) {
            $this->SetStatus(IS_ACTIVE);
        }
        $this->updateFromDeviceInfo($result);
        return true;
    }

    /**
     * Prüft die Verbindung mit den gespeicherten Einstellungen.
     */
    public function testConnection(): string
    {
        $client = $this->createClient();
        $result = $client->request('getDeviceInfo');
        if (!is_array($result)) {
            return sprintf($this->Translate('Connection failed: %s'), $client->getLastError());
        }
        return sprintf($this->Translate('Connection successful (%s)'), (string) ($result['deviceName'] ?? ''));
    }

    //Zeitquelle für die Symcon-Stubs der PHPUnit-Tests (sie rufen sie bei Timern auf), im Betrieb ohne Wirkung
    protected function getTime(): int
    {
        return time();
    }

    private function updateFromDeviceInfo(array $Info): void
    {
        $definitions = $this->getDefinitions();
        $active = $this->getActiveIdents();

        foreach ($Info as $key => $value) {
            if (!isset($definitions[$key])) {
                $this->SendDebug('Unknown key', (string) $key, 0);
                continue;
            }
            if (!($active[$key] ?? true)) {
                continue;
            }
            $definition = $definitions[$key];
            // Die Datenzähler melden -1, wenn das Gerät sie nicht liefert (zum Beispiel ohne Mobilfunk)
            if ((str_starts_with((string) $key, 'rxBytes') || str_starts_with((string) $key, 'txBytes')) && is_numeric($value) && $value < 0) {
                continue;
            }
            if ($key === 'audioVolumes' && is_array($value)) {
                // Fully liefert eine Liste mit je einem Eintrag {"Stream": Lautstärke}, daraus wird ein einziges Objekt
                $volumes = [];
                foreach ($value as $entry) {
                    foreach (is_array($entry) ? $entry : [] as $stream => $level) {
                        $volumes[(int) $stream] = (int) $level;
                    }
                }
                ksort($volumes);
                $value = $volumes;
                if (isset($volumes[3]) && ($active['volumeMusic'] ?? true)) {
                    $this->setValueIfExists('volumeMusic', $volumes[3]);
                }
            }
            if ($definition['Divisor'] !== 1 && is_numeric($value)) {
                $value = $value / $definition['Divisor'];
            }
            if ($key === 'SSID' && is_string($value)) {
                // Android liefert die SSID in Anführungszeichen
                $value = trim($value, '"');
            }
            $converted = $this->convertValue($definition['Type'], $value);
            if ($converted !== null) {
                $this->setValueIfExists((string) $key, $converted);
            }
        }
    }

    private function storeEvent(string $Event, array $Details): void
    {
        // Die Geräte-ID und der Ereignisname stehen schon im Topic, übrig bleiben die Zusatzdaten (zum Beispiel die neue URL)
        unset($Details['deviceId'], $Details['event']);
        $this->setValueIfExists('lastEvent', $Event);
        $this->setValueIfExists('lastEventData', $Details === [] ? '' : json_encode($Details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->setValueIfExists('lastEventTime', time());
    }

    private function convertValue(int $Type, mixed $Value): bool|int|float|string|null
    {
        switch ($Type) {
            case VARIABLETYPE_BOOLEAN:
                return filter_var($Value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            case VARIABLETYPE_INTEGER:
                return is_numeric($Value) ? (int) round((float) $Value) : null;
            case VARIABLETYPE_FLOAT:
                return is_numeric($Value) ? (float) $Value : null;
            default:
                if (is_array($Value)) {
                    return json_encode($Value);
                }
                if (is_bool($Value)) {
                    return $Value ? 'true' : 'false';
                }
                return (string) $Value;
        }
    }

    private function setValueIfExists(string $Ident, bool|int|float|string $Value): void
    {
        if (@IPS_GetObjectIDByIdent($Ident, $this->InstanceID) === false) {
            return;
        }
        $this->SetValue($Ident, $Value);
    }

    private function getDefinitions(): array
    {
        $apps = json_decode($this->ReadPropertyString('Apps'), true);
        return FKBVariableDefinitions::all(fn (string $Text): string => $this->Translate($Text), is_array($apps) ? $apps : []);
    }

    private function getActiveIdents(): array
    {
        $active = [];
        $variables = json_decode($this->ReadPropertyString('Variables'), true);
        foreach (is_array($variables) ? $variables : [] as $variable) {
            if (isset($variable['Ident'])) {
                $active[(string) $variable['Ident']] = (bool) ($variable['Active'] ?? true);
            }
        }
        return $active;
    }

    private function createClient(): FKBApiClient
    {
        return new FKBApiClient(
            trim($this->ReadPropertyString('Host')),
            $this->ReadPropertyInteger('Port'),
            $this->ReadPropertyString('Password'),
            $this->ReadPropertyBoolean('UseHTTPS'),
            fn (string $Message, string $Data): bool => $this->SendDebug($Message, $Data, 0)
        );
    }

    private function command(string $Command, array $Parameters = []): bool
    {
        $client = $this->createClient();
        if ($client->command($Command, $Parameters)) {
            return true;
        }
        $this->LogMessage(sprintf($this->Translate('Command %s failed: %s'), $Command, $client->getLastError()), KL_WARNING);
        return false;
    }

    private function storeImage(string $Command, string $Ident, string $Extension): bool
    {
        $client = $this->createClient();
        $image = $client->request($Command, [], false);
        if (!is_string($image) || $image === '') {
            $this->LogMessage(sprintf($this->Translate('Command %s failed: %s'), $Command, $client->getLastError()), KL_WARNING);
            return false;
        }

        $mediaId = @IPS_GetObjectIDByIdent($Ident, $this->InstanceID);
        if ($mediaId === false) {
            $mediaId = IPS_CreateMedia(MEDIATYPE_IMAGE);
            IPS_SetParent($mediaId, $this->InstanceID);
            IPS_SetIdent($mediaId, $Ident);
            IPS_SetName($mediaId, $this->Translate($Ident));
            IPS_SetMediaFile($mediaId, 'media/' . $this->InstanceID . '_' . $Ident . '.' . $Extension, false);
        }
        IPS_SetMediaContent($mediaId, base64_encode($image));
        return true;
    }
}
