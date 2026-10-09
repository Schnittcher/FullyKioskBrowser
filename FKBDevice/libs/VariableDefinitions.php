<?php

declare(strict_types=1);

/**
 * Definitionen aller Variablen der Instanz (Ident, Typ, Darstellung, Action).
 * Die Darstellungen sind native Presentations von IP-Symcon 9.
 */
class FKBVariableDefinitions
{
    public const PRESENTATION_VALUE = '{3319437D-7CDE-699D-750A-3C6A3841FA75}';
    public const PRESENTATION_ENUMERATION = '{52D9E126-D7D2-2CBB-5E62-4CF7BA7C5D82}';
    public const PRESENTATION_SWITCH = '{60AE6B26-B3E2-BDB1-A3A1-BE232940664B}';
    public const PRESENTATION_SLIDER = '{6B9CAEEC-5958-C223-30F7-BD36569FC57A}';
    public const PRESENTATION_INPUT = '{6F477326-1683-A2FD-D2E7-477F366ECB62}';
    public const PRESENTATION_DATE_TIME = '{497C4845-27FA-6E4F-AE37-5D951D3BDBF9}';

    // Konstanten für Variablentypen, damit die Tabelle unten lesbar bleibt
    private const BOOL = 0;
    private const INT = 1;
    private const FLOAT = 2;
    private const STRING = 3;

    /**
     * Liefert alle Variablen in Anzeigereihenfolge.
     *
     * @param Closure $Translate Übersetzt einen Text (Translate() des Moduls)
     * @param array $Apps Auswahl für die Apps-Variable: Liste aus ['AppName' => ..., 'Package' => ...]
     * @return array Ident => ['Caption', 'Type', 'Presentation', 'Action', 'Divisor']
     */
    public static function all(Closure $Translate, array $Apps): array
    {
        $text = ['PRESENTATION' => self::PRESENTATION_VALUE];
        $number = fn (string $Suffix, int $Digits = 0): array => ['PRESENTATION' => self::PRESENTATION_VALUE, 'SUFFIX' => $Suffix, 'DIGITS' => $Digits];
        $yesNo = self::booleanOptions($Translate('Yes'), $Translate('No'), true);
        $onOff = self::booleanOptions($Translate('On'), $Translate('Off'), false);
        $switch = ['PRESENTATION' => self::PRESENTATION_SWITCH];
        $input = ['PRESENTATION' => self::PRESENTATION_INPUT];
        $dateTime = ['PRESENTATION' => self::PRESENTATION_DATE_TIME, 'DATE' => 1, 'MONTH_TEXT' => false, 'DAY_OF_THE_WEEK' => false, 'TIME' => 2];

        $definitions = [
            'deviceId'                  => ['Device ID', self::STRING, $text],
            'deviceName'                => ['Devicename', self::STRING, $text],
            'altitude'                  => ['Altidude', self::FLOAT, $number(' m', 1)],
            'longitude'                 => ['Longitude', self::FLOAT, $number(' °', 5)],
            'latitude'                  => ['Latitude', self::FLOAT, $number(' °', 5)],
            'locationProvider'          => ['Location Provide', self::STRING, $text],
            'locationAccuracy'          => ['Location Accuracy', self::INT, $number(' m')],
            'batteryLevel'              => ['Battery', self::INT, $number(' %')],
            'batteryTemperature'        => ['Battery Temperature', self::FLOAT, $number(' °C', 1)],
            'isPlugged'                 => ['isPlugged', self::BOOL, $yesNo],
            'plugged'                   => ['Plugged', self::BOOL, $yesNo],
            'SSID'                      => ['SSID', self::STRING, $text],
            'BSSID'                     => ['BSSID', self::STRING, $text],
            'Mac'                       => ['MAC Address', self::STRING, $text],
            'ip4'                       => ['IPv4', self::STRING, $text],
            'ip6'                       => ['IPv6', self::STRING, $text],
            'hostname4'                 => ['Hostname IPv4', self::STRING, $text],
            'hostname6'                 => ['Hostname IPv6', self::STRING, $text],
            'wifiSignalLevel'           => ['Wifi Signal Level', self::INT, $number('')],
            'isMobileDataEnabled'       => ['Mobile Data', self::BOOL, $onOff],
            'isHotspotEnabled'          => ['Hotspot', self::BOOL, $onOff],
            'rxBytesMobile'             => ['Mobile Data Received', self::INT, $number(' MB'), false, 1024 ** 2],
            'txBytesMobile'             => ['Mobile Data Sent', self::INT, $number(' MB'), false, 1024 ** 2],
            'rxBytesWifi'               => ['WiFi Data Received', self::INT, $number(' MB'), false, 1024 ** 2],
            'txBytesWifi'               => ['WiFi Data Sent', self::INT, $number(' MB'), false, 1024 ** 2],
            'screenOrientation'         => ['Screen Orientation', self::INT, $number(' °')],
            'screenBrightness'          => ['Screen Brightness', self::INT, ['PRESENTATION' => self::PRESENTATION_SLIDER, 'MIN' => 0, 'MAX' => 255, 'STEP_SIZE' => 1, 'DIGITS' => 0], true],
            'displayState'              => ['Display State', self::INT, $number('')],
            'displayLetterboxed'        => ['Display Letterboxed', self::BOOL, $yesNo],
            'screenLocked'              => ['Screen Locked', self::BOOL, $onOff],
            'screenOn'                  => ['Display', self::BOOL, $switch, true],
            'keyguardLocked'            => ['Keyguard Locked', self::BOOL, $onOff],
            'locale'                    => ['Locale', self::STRING, $text],
            'serial'                    => ['Serial', self::STRING, $text],
            'version'                   => ['Version', self::STRING, $text],
            'versionCode'               => ['Version Code', self::STRING, $text],
            'build'                     => ['Build', self::STRING, $text],
            'model'                     => ['Model', self::STRING, $text],
            'manufacturer'              => ['Manufacturer', self::STRING, $text],
            'androidVersion'            => ['Android Version', self::STRING, $text],
            'SDK'                       => ['SDK', self::STRING, $text],
            'webviewUA'                 => ['webviewUA', self::STRING, $text],
            'foreground'                => ['Foreground', self::STRING, $text],
            'toForeground'              => ['To Foreground', self::INT, self::buttonOptions([0 => $Translate('Set')]), true],
            'toBackground'              => ['To Background', self::INT, self::buttonOptions([0 => $Translate('Set')]), true],
            'foregroundActivity'        => ['Foreground Activity', self::STRING, $text],
            'motionDetectorStatus'      => ['Motion Detector Status', self::INT, $number('')],
            'isDeviceAdmin'             => ['Device Admin', self::BOOL, $yesNo],
            'isDeviceOwner'             => ['Device Owner', self::BOOL, $yesNo],
            'internalStorageFreeSpace'  => ['Internal Storage Free Space', self::INT, $number(' GB'), false, 1024 ** 3],
            'internalStorageTotalSpace' => ['Internal Storage Total Space', self::INT, $number(' GB'), false, 1024 ** 3],
            'externalStorageFreeSpace'  => ['External Storage Free Space', self::INT, $number(' GB'), false, 1024 ** 3],
            'externalStorageTotalSpace' => ['External Storage Total Space', self::INT, $number(' GB'), false, 1024 ** 3],
            'ramUsedMemory'             => ['Ram Used Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'ramFreeMemory'             => ['Ram Free Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'ramTotalMemory'            => ['Ram Total Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'scopedStorage'             => ['Scoped Storage', self::BOOL, $yesNo],
            'appUsedMemory'             => ['App Used Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'appFreeMemory'             => ['App Free Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'appTotalMemory'            => ['App Total Memory', self::INT, $number(' MB'), false, 1024 ** 2],
            'displayHeightPixels'       => ['Display Height Pixels', self::INT, $number(' px')],
            'displayWidthPixels'        => ['Display Width Pixels', self::INT, $number(' px')],
            'isMenuOpen'                => ['Menu Open', self::BOOL, $yesNo],
            'topFragmentTag'            => ['Top Fragment Tag', self::STRING, $text],
            'isInDaydream'              => ['Daydream', self::BOOL, $switch, true],
            'appStartTime'              => ['App Start Time', self::STRING, $text],
            'isRooted'                  => ['Rooted', self::BOOL, $yesNo],
            'isLicensed'                => ['Licensed', self::BOOL, $yesNo],
            'isInScreensaver'           => ['Screensaver', self::BOOL, $switch, true],
            'kioskLocked'               => ['Kiosk Locked', self::BOOL, $yesNo],
            'isInForcedSleep'           => ['In Forced Sleep', self::BOOL, $yesNo],
            'maintenanceMode'           => ['Maintenance Mode', self::BOOL, $switch, true],
            'kioskMode'                 => ['Kiosk Mode', self::BOOL, $switch, true],
            'startUrl'                  => ['Start URL', self::STRING, $text],
            'currentTabIndex'           => ['Current TabIndex', self::STRING, $text],
            'currentPageUrl'            => ['Current Page URL', self::STRING, $text],
            'mqttConnected'             => ['MQTT Connected', self::BOOL, $yesNo],
            'lastUserInteractionTime'   => ['Last User Interaction', self::INT, $dateTime, false, 1000],
            'motionDetectorStarted'     => ['Motion Detector Started', self::BOOL, $yesNo],
            'installerSource'           => ['Installer Source', self::STRING, $text],
            'systemSigned'              => ['System Signed', self::BOOL, $yesNo],
            'audioVolumes'              => ['Audio Volumes', self::STRING, $text],
            'volumeMusic'               => ['Volume Music', self::INT, ['PRESENTATION' => self::PRESENTATION_SLIDER, 'MIN' => 0, 'MAX' => 100, 'STEP_SIZE' => 1, 'DIGITS' => 0, 'SUFFIX' => ' %'], true],
            'lastEvent'                 => ['Last Event', self::STRING, $text],
            'lastEventData'             => ['Last Event Data', self::STRING, $text],
            'lastEventTime'             => ['Last Event Time', self::INT, $dateTime],
            'Apps'                      => ['Apps', self::INT, self::appOptions($Apps), true],
            'packageName'               => ['Package Name', self::STRING, $text],
            'DeviceControl'             => ['Device Control', self::INT, self::buttonOptions([0 => $Translate('Shutdown'), 1 => $Translate('Reboot')]), true],
            'textToSpeech'              => ['Text to Speech', self::STRING, $input, true],
            'setOverlayMessage'         => ['Set Overlay Message', self::STRING, $input, true],
            'loadStartURL'              => ['Load Start URL', self::STRING, self::stringButtonOptions($Translate('Set')), true],
            'loadURL'                   => ['Load URL', self::STRING, $input, true],
        ];

        $result = [];
        foreach ($definitions as $ident => $definition) {
            $result[$ident] = [
                'Caption'      => $definition[0],
                'Type'         => $definition[1],
                'Presentation' => $definition[2],
                'Action'       => $definition[3] ?? false,
                'Divisor'      => $definition[4] ?? 1
            ];
        }
        return $result;
    }

    private static function booleanOptions(string $True, string $False, bool $Colored): array
    {
        $option = fn (bool $Value, string $Caption, int $Color): array => [
            'Value'              => $Value,
            'Caption'            => $Caption,
            'IconValue'          => '',
            'IconActive'         => false,
            'ColorActive'        => $Colored,
            'ColorValue'         => $Color,
            'ContentColorActive' => false
        ];
        return [
            'PRESENTATION' => self::PRESENTATION_VALUE,
            'OPTIONS'      => json_encode([$option(false, $False, 0xFF0000), $option(true, $True, 0x00FF00)])
        ];
    }

    private static function buttonOptions(array $Captions): array
    {
        $options = [];
        foreach ($Captions as $value => $caption) {
            $options[] = self::enumerationOption($value, $caption);
        }
        return ['PRESENTATION' => self::PRESENTATION_ENUMERATION, 'OPTIONS' => json_encode($options)];
    }

    private static function stringButtonOptions(string $Caption): array
    {
        return ['PRESENTATION' => self::PRESENTATION_ENUMERATION, 'OPTIONS' => json_encode([self::enumerationOption('load', $Caption)])];
    }

    private static function appOptions(array $Apps): array
    {
        $options = [];
        foreach (array_values($Apps) as $index => $app) {
            $options[] = self::enumerationOption($index + 1, (string) ($app['AppName'] ?? ''));
        }
        return ['PRESENTATION' => self::PRESENTATION_ENUMERATION, 'OPTIONS' => json_encode($options)];
    }

    private static function enumerationOption(int|string $Value, string $Caption): array
    {
        return ['Value' => $Value, 'Caption' => $Caption, 'IconValue' => '', 'IconActive' => false, 'Color' => -1];
    }
}
