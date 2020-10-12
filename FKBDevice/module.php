<?php

declare(strict_types=1);

require_once __DIR__ . '/../libs/VariableProfileHelper.php';  // diverse Klassen

class FKBDevice extends IPSModule
{
    use VariableProfileHelper;

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 2323);
        $this->RegisterPropertyString('Password', '');
        $this->RegisterPropertyInteger('Updateinterval', 10);
        $this->RegisterPropertyString('Apps', '[]');

        $this->RegisterVariableString('deviceId', $this->Translate('Geräte ID'));
        $this->RegisterVariableString('deviceName', $this->Translate('Geräte Name'));
        $this->RegisterVariableFloat('altitude', $this->Translate('Altidude'));
        $this->RegisterVariableFloat('longitude', $this->Translate('Longitude'));
        $this->RegisterVariableFloat('latitude', $this->Translate('Latitude'));
        $this->RegisterVariableString('locationProvide', $this->Translate('Standort ermittelt durch'));
        $this->RegisterVariableFloat('batteryLevel', $this->Translate('Batterie Ladestand'));
        $this->RegisterVariableFloat('batteryTemperature', $this->Translate('Batterie Temperatur'));
        $this->RegisterVariableBoolean('isPlugged', $this->Translate('Wird geladen'), '~Switch');
        $this->RegisterVariableBoolean('plugged', $this->Translate('Wird geladen'), '~Switch');

        $this->RegisterVariableString('SSID', $this->Translate('SSID'));
        $this->RegisterVariableString('Mac', $this->Translate('MAC Adresse'));
        $this->RegisterVariableString('ip4', $this->Translate('IPv4'));
        $this->RegisterVariableString('ip6', $this->Translate('IPv6'));
        $this->RegisterVariableString('hostname4', $this->Translate('Hostname IPv4'));
        $this->RegisterVariableString('hostname6', $this->Translate('Hostname IPv6'));
        $this->RegisterVariableInteger('wifiSignalLevel', $this->Translate('Wifi Signalstärke'));
        $this->RegisterVariableBoolean('isMobileDataEnabled', $this->Translate('Mobile Daten eingeschaltet?'), '~Switch');

        $this->RegisterVariableInteger('screenOrientation', $this->Translate('Bildschirm Ausrichtung'));
        $this->RegisterVariableInteger('screenBrightness', $this->Translate('Bildschirm Helligkeit'), '~Intensity.255');
        $this->RegisterVariableBoolean('screenLocked', $this->Translate('Bildschirm gesperrt'), '~Switch');
        $this->RegisterVariableBoolean('screenOn', $this->Translate('Bildschirm Ein'), '~Switch');
        $this->EnableAction('screenOn');
        $this->RegisterVariableBoolean('keyguardLocked', $this->Translate('Zugriffsschutz aktiv'), '~Switch');

        $this->RegisterVariableString('locale', $this->Translate('Sprache'));
        $this->RegisterVariableString('serial', $this->Translate('Seriennummer'));
        $this->RegisterVariableString('version', $this->Translate('Version'));
        $this->RegisterVariableString('versionCode', $this->Translate('Version Code'));
        $this->RegisterVariableString('build', $this->Translate('Build'));
        $this->RegisterVariableString('model', $this->Translate('Modell'));
        $this->RegisterVariableString('manufacturer', $this->Translate('Hersteller'));
        $this->RegisterVariableString('androidVersion', $this->Translate('Android Version'));
        $this->RegisterVariableString('SDK', $this->Translate('SDK'));
        $this->RegisterVariableString('webviewUA', $this->Translate('webviewUA'));
        $this->RegisterVariableString('foreground', $this->Translate('Vordergrund'));
        $this->RegisterVariableBoolean('toForeground', $this->Translate('In den Vordergrund'), '~Switch');
        $this->EnableAction('toForeground');
        $this->RegisterVariableString('foregroundActivity', $this->Translate('Vordergrund Aktivität'));
        $this->RegisterVariableInteger('motionDetectorStatus', $this->Translate('Bewegungssensor Status'));
        $this->RegisterVariableBoolean('isDeviceAdmin', $this->Translate('Geräte Administrator'));
        $this->RegisterVariableBoolean('isDeviceOwner', $this->Translate('Geräte Eigentümer'));
        $this->RegisterVariableInteger('internalStorageFreeSpace', $this->Translate('Interner Speicher: frei'));
        $this->RegisterVariableInteger('internalStorageTotalSpace', $this->Translate('Interner Speicher: Gesamt'));
        $this->RegisterVariableInteger('ramUsedMemory', $this->Translate('Ram: genutzt'));
        $this->RegisterVariableInteger('ramFreeMemory', $this->Translate('Ram frei'));
        $this->RegisterVariableInteger('ramTotalMemory', $this->Translate('Ram gesamt'));

        $this->RegisterVariableInteger('appUsedMemory', $this->Translate('App-Speicher: genutzt'));
        $this->RegisterVariableInteger('appFreeMemory', $this->Translate('App-Speicher: frei'));
        $this->RegisterVariableInteger('appTotalMemory', $this->Translate('App-Speicher: gesamt'));

        $this->RegisterVariableInteger('displayHeightPixels', $this->Translate('Bildschirmauflösung: Höhe'));
        $this->RegisterVariableInteger('displayWidthPixels', $this->Translate('Bildschirmauflösung: Breite'));
        $this->RegisterVariableBoolean('isMenuOpen', $this->Translate('Menü geöffnet'));
        $this->RegisterVariableString('topFragmentTag', $this->Translate('Top Fragment Tag'));
        $this->RegisterVariableBoolean('isInDaydream', $this->Translate('Wird Daydream genutzt?'), '~Switch');
        $this->EnableAction('isInDaydream');
        $this->RegisterVariableString('appStartTime', $this->Translate('App läuft seit'));
        $this->RegisterVariableBoolean('isRooted', $this->Translate('Rooted'), '~Switch');
        $this->RegisterVariableBoolean('isLicensed', $this->Translate('Licensiert'), '~Switch');
        $this->RegisterVariableBoolean('isInScreensaver', $this->Translate('Screensaver aktiviert?'), '~Switch');
        $this->EnableAction('isInScreensaver');
        $this->RegisterVariableBoolean('kioskLocked', $this->Translate('Kiosk Locked'), '~Switch');
        $this->RegisterVariableBoolean('isInForcedSleep', $this->Translate('Erzwungener Schlafmodus'), '~Switch');
        $this->RegisterVariableBoolean('maintenanceMode', $this->Translate('Wartungsmodus'), '~Switch');
        $this->RegisterVariableBoolean('kioskMode', $this->Translate('Kiosk Modus'), '~Switch');
        $this->EnableAction('kioskMode');
        $this->RegisterVariableString('startUrl', $this->Translate('Anfangs-URL'));
        $this->RegisterVariableString('currentTabIndex', $this->Translate('Aktueller TabIndex'));
        $this->RegisterVariableString('currentPageUrl', $this->Translate('Aktuelle Seiten-URL'));

        $this->RegisterVariableString('textToSpeech', $this->Translate('Vorlesefunktion'));
        $this->EnableAction('textToSpeech');

        $this->RegisterVariableInteger('Applications', $this->Translate('Applikationen'));

        if (!IPS_VariableProfileExists('FKB.Apps')) {
            $this->RegisterProfileIntegerEx('FKB.Apps', 'Database', '', '', []);
        }
        $this->RegisterVariableInteger('Apps', 'Apps', 'FKB.Apps', 5);
        $this->EnableAction('Apps');

        $this->RegisterTimer('FKB_Update', 0, 'FKB_getDeviceInfo($_IPS[\'TARGET\']);');
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->updateAppsList();
        $this->SetTimerInterval('FKB_Update', $this->ReadPropertyInteger('Updateinterval') * 1000);
    }

    public function screen(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=screenOn');
        } else {
            $result = $this->sendRequest('?cmd=screenOff');
        }
        if ($this->checkRequest($result)) {
            $this->SetValue('screenOn', $Value);
            return true;
        }
    }

    public function screensaver(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=startScreensaver');
        } else {
            $result = $this->sendRequest('?cmd=stopScreensaver');
        }
        if ($this->checkRequest($result)) {
            $this->SetValue('isInScreensaver', $Value);
            return true;
        }
    }

    public function daydream(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=startDaydream');
        } else {
            $result = $this->sendRequest('?cmd=stopDaydream');
        }
        if ($this->checkRequest($result)) {
            $this->SetValue('isInDaydream', $Value);
            return true;
        }
    }

    public function kioskMode(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=lockKiosk');
        } else {
            $result = $this->sendRequest('?cmd=unlockKiosk');
        }
        if ($this->checkRequest($result)) {
            $this->SetValue('kioskMode', $Value);
            return true;
        }
    }

    public function startApplication(string $Value)
    {
        $result = $this->sendRequest('?cmd=startApplication&package=' . $Value);
        if ($this->checkRequest($result)) {
            return true;
        }
    }

    public function toForeground(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=toForeground');
        }
        if ($this->checkRequest($result)) {
            return true;
        }
    }

    public function getDeviceInfo()
    {
        $result = $this->sendRequest('?cmd=getDeviceInfo');

        foreach ($result as $key => $value) {
            $this->SetValue($key, $value);
        }
    }

    public function textToSpeech(string $value)
    {
        $result = $this->sendRequest('?cmd=textToSpeech&text=' . urlencode($value));
        return $this->checkRequest($result);
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'screenOn':
                $this->screen($Value);
                break;
            case 'textToSpeech':
                $this->textToSpeech($Value);
                break;
            case 'isInScreensaver':
                $this->screensaver($Value);
                break;
            case 'isInDaydream':
                $this->daydream($Value);
                break;
            case 'kioskMode':
                $this->kioskMode($Value);
                break;
            case 'toForeground':
                $this->toForeground($Value);
                break;
            case 'Apps':
                $AppsListString = $this->ReadPropertyString('Apps');
                $Apps = json_decode($AppsListString);
                $App = $Apps[$Value - 1];
                $this->startApplication($App->Package);
            break;
        }
    }

    private function checkRequest($Value)
    {
        if ($Value['status'] == 'OK') {
            return true;
        } else {
            return false;
        }
    }

    private function updateAppsList()
    {
        $AppsListString = $this->ReadPropertyString('Apps');

        if ($AppsListString != '') {
            if (IPS_VariableProfileExists('FKB.Apps')) {
                IPS_DeleteVariableProfile('FKB.Apps');
            }

            $Associations = [];
            $Value = 1;

            $Apps = json_decode($AppsListString);
            foreach ($Apps as $App) {
                $Associations[] = [$Value++, $App->AppName, '', -1];
                // associations only support up to 32 variables
                if ($Value === 33) {
                    break;
                }
            }
            $this->RegisterProfileIntegerEx('FKB.Apps', 'Database', '', '', $Associations);
        }
    }

    private function sendRequest($params)
    {
        $host = $this->ReadPropertyString('Host');
        $port = $this->ReadPropertyInteger('Port');
        $password = $this->ReadPropertyString('Password');
        $url = 'http://' . $host . ':' . $port . '/' . $params . '&password=' . $password . '&type=json';

        $this->SendDebug('Request URL', $url, 0);
        $json = @file_get_contents($url);
        if ($json === false) {
            return false;
        } else {
            $data = json_decode($json, true);
            return $data;
        }
    }
}
