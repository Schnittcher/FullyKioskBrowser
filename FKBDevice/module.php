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
        $this->RegisterPropertyString('Variables', '[]');

        $this->registerVariableProfiles();

        if (!IPS_VariableProfileExists('FKB.Apps')) {
            $this->RegisterProfileIntegerEx('FKB.Apps', 'Database', '', '', []);
        }

        /**
         * $this->RegisterVariableString('deviceName', $this->Translate('Devicename'), 'FKB.Information', 1);
         * $this->RegisterVariableFloat('altitude', $this->Translate('Altidude'), '', 58);
         * $this->RegisterVariableFloat('longitude', $this->Translate('Longitude'), '', 55);
         * $this->RegisterVariableFloat('latitude', $this->Translate('Latitude'), '', 54);
         * $this->RegisterVariableString('locationProvide', $this->Translate('Location Provide'), 'FKB.Information', 52);
         * $this->RegisterVariableInteger('batteryLevel', $this->Translate('Battery'), '~Battery.100', 9);
         * $this->RegisterVariableFloat('batteryTemperature', $this->Translate('Battery Temperature'), '~Temperature', 12);
         * $this->RegisterVariableBoolean('isPlugged', $this->Translate('isPlugged'), 'FKB.YesNo', 10);
         * $this->RegisterVariableBoolean('plugged', $this->Translate('Plugged'), 'FKB.YesNo', 11);
         *
         * $this->RegisterVariableString('SSID', $this->Translate('SSID'), 'FKB.Network', 20);
         * $this->RegisterVariableString('BSSID', $this->Translate('BSSID'), 'FKB.Network', 20);
         * $this->RegisterVariableString('Mac', $this->Translate('MAC Address'), 'FKB.Network', 18);
         * $this->RegisterVariableString('ip4', $this->Translate('IPv4'), 'FKB.Network', 15);
         * $this->RegisterVariableString('ip6', $this->Translate('IPv6'), 'FKB.Network', 17);
         * $this->RegisterVariableString('hostname4', $this->Translate('Hostname IPv4'), 'FKB.Network', 14);
         * $this->RegisterVariableString('hostname6', $this->Translate('Hostname IPv6'), 'FKB.Network', 16);
         * $this->RegisterVariableInteger('wifiSignalLevel', $this->Translate('Wifi Signal Level'), '', 19);
         * $this->RegisterVariableBoolean('isMobileDataEnabled', $this->Translate('Mobile Data'), '~Switch', 21);
         *
         * $this->RegisterVariableInteger('screenOrientation', $this->Translate('Screen Orientation'), '', 34);
         * $this->RegisterVariableInteger('screenBrightness', $this->Translate('Screen Brightness'), '~Intensity.255', 35);
         * $this->RegisterVariableBoolean('screenLocked', $this->Translate('Screen Locked'), '~Switch', 31);
         * $this->RegisterVariableBoolean('screenOn', $this->Translate('Display'), '~Switch', 30);
         * $this->EnableAction('screenOn');
         * $this->RegisterVariableBoolean('keyguardLocked', $this->Translate('Keyguard Locked'), '~Switch', 60);
         *
         * $this->RegisterVariableString('locale', $this->Translate('Locale'), 'FKB.Information', 6);
         * $this->RegisterVariableString('serial', $this->Translate('Serial'), 'FKB.Information', 5);
         * $this->RegisterVariableString('version', $this->Translate('Version'), 'FKB.Information', 41);
         * $this->RegisterVariableString('versionCode', $this->Translate('Version Code'), 'FKB.Information', 42);
         * $this->RegisterVariableString('build', $this->Translate('Build'), 'FKB.Information', 45);
         * $this->RegisterVariableString('model', $this->Translate('Model'), 'FKB.Information', 4);
         * $this->RegisterVariableString('manufacturer', $this->Translate('Manufacturer'), 'FKB.Information', 3);
         * $this->RegisterVariableString('androidVersion', $this->Translate('Android Version'), 'FKB.Information', 7);
         * $this->RegisterVariableString('SDK', $this->Translate('SDK'), 'FKB.Information', 53);
         * $this->RegisterVariableString('webviewUA', $this->Translate('webviewUA'), 'FKB.Information', 43);
         * $this->RegisterVariableString('foreground', $this->Translate('Foreground'), 'FKB.Information', 46);
         * $this->RegisterVariableInteger('toForeground', $this->Translate('To Foreground'), 'FKB.Set', 44);
         * $this->EnableAction('toForeground');
         * $this->RegisterVariableString('foregroundActivity', $this->Translate('Foreground Activity'), 'FKB.Information', 59);
         * $this->RegisterVariableInteger('motionDetectorStatus', $this->Translate('Motion Detector Status'), '', 53);
         * $this->RegisterVariableBoolean('isDeviceAdmin', $this->Translate('Device Admin'), 'FKB.YesNo', 62);
         * $this->RegisterVariableBoolean('isDeviceOwner', $this->Translate('Device Owner'), 'FKB.YesNo', 63);
         * $this->RegisterVariableInteger('internalStorageFreeSpace', $this->Translate('Internal Storage Free Space'), 'FKB.SpaceGB', 25);
         * $this->RegisterVariableInteger('internalStorageTotalSpace', $this->Translate('Internal Storage Total Space'), 'FKB.SpaceGB', 26);
         * $this->RegisterVariableInteger('ramUsedMemory', $this->Translate('Ram Used Memory'), 'FKB.SpaceMB', 23);
         * $this->RegisterVariableInteger('ramFreeMemory', $this->Translate('Ram Free Memory'), 'FKB.SpaceMB', 22);
         * $this->RegisterVariableInteger('ramTotalMemory', $this->Translate('Ram Total Memory'), 'FKB.SpaceMB', 24);
         * $this->RegisterVariableBoolean('scopedStorage', $this->Translate('Scoped Storage'), 'FKB.YesNo', 25);
         * $this->RegisterVariableInteger('appUsedMemory', $this->Translate('App Used Memory'), 'FKB.SpaceMB', 28);
         * $this->RegisterVariableInteger('appFreeMemory', $this->Translate('App Free Memory'), 'FKB.SpaceMB', 27);
         * $this->RegisterVariableInteger('appTotalMemory', $this->Translate('App Total Memory'), 'FKB.SpaceMB', 29);
         *
         * $this->RegisterVariableInteger('displayHeightPixels', $this->Translate('Display Height Pixels'), 'FKB.Resolution', 33);
         * $this->RegisterVariableInteger('displayWidthPixels', $this->Translate('Display Width Pixels'), 'FKB.Resolution', 32);
         * $this->RegisterVariableBoolean('isMenuOpen', $this->Translate('Menu Open'), 'FKB.YesNo', 49);
         * $this->RegisterVariableString('topFragmentTag', $this->Translate('Top Fragment Tag'), 'FKB.Information', 65);
         * $this->RegisterVariableBoolean('isInDaydream', $this->Translate('Daydream'), '~Switch', 61);
         * $this->EnableAction('isInDaydream');
         * $this->RegisterVariableString('appStartTime', $this->Translate('App Start Time'), 'FKB.Information', 37);
         * $this->RegisterVariableBoolean('isRooted', $this->Translate('Rooted'), 'FKB.YesNo', 63);
         * $this->RegisterVariableBoolean('isLicensed', $this->Translate('Licensed'), 'FKB.YesNo', 40);
         * $this->RegisterVariableBoolean('isInScreensaver', $this->Translate('Screensaver'), '~Switch', 36);
         * $this->EnableAction('isInScreensaver');
         * $this->RegisterVariableBoolean('kioskLocked', $this->Translate('Kiosk Locked'), 'FKB.YesNo', 39);
         * $this->RegisterVariableBoolean('isInForcedSleep', $this->Translate('In Forced Sleep'), 'FKB.YesNo', 13);
         * $this->RegisterVariableBoolean('maintenanceMode', $this->Translate('Maintenance Mode'), '~Switch', 8);
         * $this->EnableAction('maintenanceMode');
         * $this->RegisterVariableBoolean('kioskMode', $this->Translate('Kiosk Mode'), '~Switch', 38);
         * $this->EnableAction('kioskMode');
         * $this->RegisterVariableString('startUrl', $this->Translate('Start URL'), '', 45);
         * $this->RegisterVariableString('currentTabIndex', $this->Translate('Current TabIndex'), 'FKB.Information', 48);
         * $this->RegisterVariableString('currentPageUrl', $this->Translate('Current Page URL'), 'FKB.Information', 47);
         *
         * $this->RegisterVariableString('textToSpeech', $this->Translate('Text to Speech'), 'FKB.TextToSpeech', 56);
         * $this->EnableAction('textToSpeech');
         *
         * $this->RegisterVariableBoolean('mqttConnected', $this->Translate('MQTT Connected'), 'FKB.YesNo', 66);
         *
         * $this->RegisterVariableInteger('externalStorageFreeSpace', $this->Translate('External Storage Free Space'), 'FKB.SpaceGB', 67);
         * $this->RegisterVariableInteger('externalStorageTotalSpace', $this->Translate('External Storage Total Space'), 'FKB.SpaceGB', 68);
         *
         * $this->RegisterVariableInteger('Apps', $this->Translate('Apps'), 'FKB.Apps');
         * $this->EnableAction('Apps');
         *
         * $this->RegisterVariableString('packageName', $this->Translate('Package Name'), '', 68);
         *
         * $this->RegisterVariableInteger('DeviceControl', $this->Translate('Device Control'), 'FKB.DeviceControl');
         * $this->EnableAction('DeviceControl');
         *
         * $this->RegisterVariableString('setOverlayMessage', $this->Translate('Set Overlay Message'));
         * $this->EnableAction('setOverlayMessage');
         */
        $this->RegisterTimer('FKB_Update', 0, 'FKB_getDeviceInfo($_IPS[\'TARGET\']);');
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function GetConfigurationForm()
    {
        $Form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        $countVariables = count($Form['elements'][4]['values']);

        for ($i = 0; $i <= $countVariables - 1; $i++) {
            $Form['elements'][4]['values'][$i]['Variable'] = $this->Translate($Form['elements'][4]['values'][$i]['Variable']);
        }
        return json_encode($Form);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->updateAppsList();
        $this->SetTimerInterval('FKB_Update', $this->ReadPropertyInteger('Updateinterval') * 1000);

        $VariablesListString = $this->ReadPropertyString('Variables');
        $Variables = json_decode($VariablesListString, true);
        $VariablesBuffer = [];
        foreach ($Variables as $Variable) {
            $VariablesBuffer[$Variable['Ident']]['Active'] = $Variable['Active'];
            switch ($Variable['Ident']) {
                case 'deviceName':
                    $this->MaintainVariable('deviceName', $this->Translate('Devicename'), 3, 'FKB.Information', 1, $Variable['Active']);
                    break;
                case 'deviceId':
                    $this->MaintainVariable('deviceId', $this->Translate('Device ID'), 3, 'FKB.Information', 2, $Variable['Active']);
                    break;
                case 'altitude':
                    $this->MaintainVariable('altitude', $this->Translate('Altidude'), 2, '', 58, $Variable['Active']);
                    break;
                case 'longitude':
                    $this->MaintainVariable('longitude', $this->Translate('Longitude'), 2, '', 55, $Variable['Active']);
                    break;
                case 'latitude':
                    $this->MaintainVariable('latitude', $this->Translate('Latitude'), 2, '', 54, $Variable['Active']);
                    break;
                case 'locationProvide':
                    $this->MaintainVariable('locationProvide', $this->Translate('Location Provide'), 3, 'FKB.Information', 52, $Variable['Active']);
                    break;
                case 'batteryLevel':
                    $this->MaintainVariable('batteryLevel', $this->Translate('Battery'), 1, '~Battery.100', 9, $Variable['Active']);
                    break;
                case 'batteryTemperature':
                    $this->MaintainVariable('batteryTemperature', $this->Translate('Battery Temperature'), 2, '~Temperature', 12, $Variable['Active']);
                    break;
                case 'isPlugged':
                    $this->MaintainVariable('isPlugged', $this->Translate('isPlugged'), 0, 'FKB.YesNo', 10, $Variable['Active']);
                    break;
                case 'plugged':
                    $this->MaintainVariable('plugged', $this->Translate('plugged'), 0, 'FKB.YesNo', 11, $Variable['Active']);
                    break;
                case 'SSID':
                    $this->MaintainVariable('SSID', $this->Translate('SSID'), 3, 'FKB.Information', 20, $Variable['Active']);
                    break;
                case 'BSSID':
                    $this->MaintainVariable('BSSID', $this->Translate('BSSID'), 3, 'FKB.Information', 20, $Variable['Active']);
                    break;
                case 'Mac':
                    $this->MaintainVariable('Mac', $this->Translate('MAC Address'), 3, 'FKB.Information', 18, $Variable['Active']);
                    break;
                case 'ip4':
                    $this->MaintainVariable('ip4', $this->Translate('IPv4'), 3, 'FKB.Information', 15, $Variable['Active']);
                    break;
                case 'ip6':
                    $this->MaintainVariable('ip6', $this->Translate('IPv6'), 3, 'FKB.Information', 17, $Variable['Active']);
                    break;
                case 'hostname4':
                    $this->MaintainVariable('hostname4', $this->Translate('Hostname IPv4'), 3, 'FKB.Information', 14, $Variable['Active']);
                    break;
                case 'hostname6':
                    $this->MaintainVariable('hostname6', $this->Translate('Hostname IPv6'), 3, 'FKB.Information', 16, $Variable['Active']);
                    break;
                case 'wifiSignalLevel':
                    $this->MaintainVariable('wifiSignalLevel', $this->Translate('Wifi Signal Level'), 1, '', 19, $Variable['Active']);
                    break;
                case 'isMobileDataEnabled':
                    $this->MaintainVariable('isMobileDataEnabled', $this->Translate('Mobile Data'), 0, '~Switch', 21, $Variable['Active']);
                    break;
                case 'screenOrientation':
                    $this->MaintainVariable('screenOrientation', $this->Translate('Screen Orientation'), 1, '', 34, $Variable['Active']);
                    break;
                case 'screenBrightness':
                    $this->MaintainVariable('screenBrightness', $this->Translate('Screen Brightness'), 1, '~Intensity.255', 35, $Variable['Active']);
                    break;
                case 'screenLocked':
                    $this->MaintainVariable('screenLocked', $this->Translate('Screen Locked'), 0, '~Switch', 31, $Variable['Active']);
                    break;
                case 'screenOn':
                    $this->MaintainVariable('screenOn', $this->Translate('Display'), 0, '~Switch', 30, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('screenOn');
                    }
                    break;
                case 'keyguardLocked':
                    $this->MaintainVariable('keyguardLocked', $this->Translate('Keyguard Locked'), 0, '~Switch', 60, $Variable['Active']);
                    break;
                case 'locale':
                    $this->MaintainVariable('locale', $this->Translate('Locale'), 3, 'FKB.Information', 6, $Variable['Active']);
                    break;
                case 'serial':
                    $this->MaintainVariable('serial', $this->Translate('Serial'), 3, 'FKB.Information', 5, $Variable['Active']);
                    break;
                case 'version':
                    $this->MaintainVariable('version', $this->Translate('Version'), 3, 'FKB.Information', 41, $Variable['Active']);
                    break;
                case 'versionCode':
                    $this->MaintainVariable('versionCode', $this->Translate('Version Code'), 3, 'FKB.Information', 42, $Variable['Active']);
                    break;
                case 'build':
                    $this->MaintainVariable('build', $this->Translate('Build'), 3, 'FKB.Information', 45, $Variable['Active']);
                    break;
                case 'model':
                    $this->MaintainVariable('model', $this->Translate('Model'), 3, 'FKB.Information', 4, $Variable['Active']);
                    break;
                case 'manufacturer':
                    $this->MaintainVariable('manufacturer', $this->Translate('manufacturer'), 3, 'FKB.Information', 3, $Variable['Active']);
                    break;
                case 'androidVersion':
                    $this->MaintainVariable('androidVersion', $this->Translate('Android Version'), 3, 'FKB.Information', 7, $Variable['Active']);
                    break;
                case 'SDK':
                    $this->MaintainVariable('SDK', $this->Translate('SDK'), 3, 'FKB.Information', 53, $Variable['Active']);
                    break;
                case 'webviewUA':
                    $this->MaintainVariable('webviewUA', $this->Translate('webviewUA'), 3, 'FKB.Information', 43, $Variable['Active']);
                    break;
                case 'foreground':
                    $this->MaintainVariable('foreground', $this->Translate('Foreground'), 3, 'FKB.Information', 46, $Variable['Active']);
                    break;
                case 'toForeground':
                    $this->MaintainVariable('toForeground', $this->Translate('To Foreground'), 1, 'FKB.Set', 53, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('toForeground');
                    }
                    break;
                case 'foregroundActivity':
                    $this->MaintainVariable('foregroundActivity', $this->Translate('Foreground Activity'), 3, 'FKB.Information', 59, $Variable['Active']);
                    break;
                case 'motionDetectorStatus':
                    $this->MaintainVariable('motionDetectorStatus', $this->Translate('Motion Detector Status'), 1, '', 53, $Variable['Active']);
                    break;
                case 'isDeviceAdmin':
                    $this->MaintainVariable('isDeviceAdmin', $this->Translate('Device Admin'), 0, 'FKB.YesNo', 62, $Variable['Active']);
                    break;
                case 'isDeviceOwner':
                    $this->MaintainVariable('isDeviceOwner', $this->Translate('Device Owner'), 0, 'FKB.YesNo', 63, $Variable['Active']);
                    break;
                case 'internalStorageFreeSpace':
                    $this->MaintainVariable('internalStorageFreeSpace', $this->Translate('Internal Storage Free Space'), 1, 'FKB.SpaceGB', 25, $Variable['Active']);
                    break;
                case 'internalStorageTotalSpace':
                    $this->MaintainVariable('internalStorageTotalSpace', $this->Translate('Internal Storage Total Space'), 1, 'FKB.SpaceGB', 26, $Variable['Active']);
                    break;
                case 'ramUsedMemory':
                    $this->MaintainVariable('ramUsedMemory', $this->Translate('Ram Used Memory'), 1, 'FKB.SpaceMB', 23, $Variable['Active']);
                    break;
                case 'ramFreeMemory':
                    $this->MaintainVariable('ramFreeMemory', $this->Translate('Ram Free Memory'), 1, 'FKB.SpaceMB', 22, $Variable['Active']);
                    break;
                case 'ramTotalMemory':
                    $this->MaintainVariable('ramTotalMemory', $this->Translate('Ram Total Memory'), 1, 'FKB.SpaceMB', 24, $Variable['Active']);
                    break;
                case 'scopedStorage':
                    $this->MaintainVariable('scopedStorage', $this->Translate('Scoped Storage'), 0, 'FKB.YesNo', 25, $Variable['Active']);
                    break;
                case 'appUsedMemory':
                    $this->MaintainVariable('appUsedMemory', $this->Translate('App Used Memory'), 1, 'FKB.SpaceMB', 28, $Variable['Active']);
                    break;
                case 'appFreeMemory':
                    $this->MaintainVariable('appFreeMemory', $this->Translate('App Free Memory'), 1, 'FKB.SpaceMB', 27, $Variable['Active']);
                    break;
                case 'appTotalMemory':
                    $this->MaintainVariable('appTotalMemory', $this->Translate('App Total Memory'), 1, 'FKB.SpaceMB', 29, $Variable['Active']);
                    break;
                case 'displayHeightPixels':
                    $this->MaintainVariable('displayHeightPixels', $this->Translate('Display Height Pixels'), 1, 'FKB.Resolution', 33, $Variable['Active']);
                    break;
                case 'displayWidthPixels':
                    $this->MaintainVariable('displayWidthPixels', $this->Translate('Display Width Pixels'), 1, 'FKB.Resolution', 32, $Variable['Active']);
                    break;
                case 'isMenuOpen':
                    $this->MaintainVariable('isMenuOpen', $this->Translate('Menu Open'), 0, 'FKB.YesNo', 49, $Variable['Active']);
                    break;
                case 'topFragmentTag':
                    $this->MaintainVariable('topFragmentTag', $this->Translate('Top Fragment Tag'), 3, 'FKB.Information', 65, $Variable['Active']);
                    break;
                case 'isInDaydream':
                    $this->MaintainVariable('isInDaydream', $this->Translate('Daydream'), 0, '~Switch', 61, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('isInDaydream');
                    }
                    break;
                case 'appStartTime':
                    $this->MaintainVariable('appStartTime', $this->Translate('App Start Time'), 3, 'FKB.Information', 37, $Variable['Active']);
                    break;
                case 'isRooted':
                    $this->MaintainVariable('isRooted', $this->Translate('Rooted'), 0, 'FKB.YesNo', 63, $Variable['Active']);
                    break;
                case 'isLicensed':
                    $this->MaintainVariable('isLicensed', $this->Translate('Licensed'), 0, 'FKB.YesNo', 40, $Variable['Active']);
                    break;
                case 'isInScreensaver':
                    $this->MaintainVariable('isInScreensaver', $this->Translate('Screensaver'), 0, '~Switch', 36, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('isInScreensaver');
                    }
                    break;
                case 'kioskLocked':
                    $this->MaintainVariable('kioskLocked', $this->Translate('Kiosk Locked'), 0, 'FKB.YesNo', 39, $Variable['Active']);
                    break;
                case 'isInForcedSleep':
                    $this->MaintainVariable('isInForcedSleep', $this->Translate('In Forced Sleep'), 0, 'FKB.YesNo', 13, $Variable['Active']);
                    break;
                case 'maintenanceMode':
                    $this->MaintainVariable('maintenanceMode', $this->Translate('Maintenance Mode'), 0, '~Switch', 8, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('maintenanceMode');
                    }
                    break;
                case 'kioskMode':
                    $this->MaintainVariable('kioskMode', $this->Translate('Kiosk Mode'), 0, '~Switch', 38, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('kioskMode');
                    }
                    break;
                case 'startUrl':
                    $this->MaintainVariable('startUrl', $this->Translate('Start URL'), 3, 'FKB.Information', 45, $Variable['Active']);
                    break;
                case 'currentTabIndex':
                    $this->MaintainVariable('currentTabIndex', $this->Translate('Current TabIndex'), 3, 'FKB.Information', 48, $Variable['Active']);
                    break;
                case 'currentPageUrl':
                    $this->MaintainVariable('currentPageUrl', $this->Translate('Current Page URL'), 3, 'FKB.Information', 47, $Variable['Active']);
                    break;
                case 'textToSpeech':
                    $this->MaintainVariable('textToSpeech', $this->Translate('Text to Speech'), 3, 'FKB.TextToSpeech', 56, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('textToSpeech');
                    }
                    break;
                case 'mqttConnected':
                    $this->MaintainVariable('mqttConnected', $this->Translate('MQTT Connected'), 0, 'FKB.YesNo', 66, $Variable['Active']);
                    break;
                case 'externalStorageFreeSpace':
                    $this->MaintainVariable('externalStorageFreeSpace', $this->Translate('External Storage Free Space'), 1, 'FKB.SpaceGB', 67, $Variable['Active']);
                    break;
                case 'externalStorageTotalSpace':
                    $this->MaintainVariable('externalStorageTotalSpace', $this->Translate('External Storage Total Space'), 1, 'FKB.SpaceGB', 68, $Variable['Active']);
                    break;
                case 'Apps':
                    $this->MaintainVariable('Apps', $this->Translate('Apps'), 1, 'FKB.Apps', 0, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('Apps');
                    }
                    break;
                case 'packageName':
                    $this->MaintainVariable('packageName', $this->Translate('Package Name'), 3, 'FKB.Information', 68, $Variable['Active']);
                    break;
                case 'DeviceControl':
                    $this->MaintainVariable('DeviceControl', $this->Translate('Device Control'), 1, 'FKB.DeviceControl', 0, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('DeviceControl');
                    }
                    break;
                case 'setOverlayMessage':
                    $this->MaintainVariable('setOverlayMessage', $this->Translate('Set Overlay Message'), 3, '', 0, $Variable['Active']);
                    if ($Variable['Active']) {
                        $this->EnableAction('setOverlayMessage');
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->SetBuffer('Variables', json_encode($VariablesBuffer));
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

    public function toForeground()
    {
        $result = $this->sendRequest('?cmd=toForeground');
        if ($this->checkRequest($result)) {
            return true;
        }
    }

    public function popFragment()
    {
        $result = $this->sendRequest('?cmd=popFragment');
        if ($this->checkRequest($result)) {
            return true;
        }
    }

    public function loadApkFile(string $Value)
    {
        $result = $this->sendRequest('/?cmd=loadApkFile&url=' . urldecode($Value));
        if ($this->checkRequest($result)) {
            return true;
        }
    }

    public function maintenanceMode(bool $Value)
    {
        if ($Value) {
            $result = $this->sendRequest('?cmd=enableLockedMode');
        } else {
            $result = $this->sendRequest('?cmd=disableLockedMode');
        }
        if ($this->checkRequest($result)) {
            $this->SetValue('maintenanceMode', $Value);
            return true;
        }
    }

    public function setOverlayMessage(string $Value)
    {
        $result = $this->sendRequest('?cmd=setOverlayMessage&text=' . urlencode($Value));
        return $this->checkRequest($result);
    }

    public function shutdownDevice()
    {
        $result = $this->sendRequest('?cmd=shutdownDevice');
        return $this->checkRequest($result);
    }

    public function rebootDevice()
    {
        $result = $this->sendRequest('?cmd=rebootDevice');
        return $this->checkRequest($result);
    }

    public function textToSpeech(string $Value)
    {
        $result = $this->sendRequest('?cmd=textToSpeech&text=' . urlencode($Value));
        return $this->checkRequest($result);
    }

    public function getDeviceInfo()
    {
        $result = $this->sendRequest('?cmd=getDeviceInfo');
        if ($result != false) {
            $VariablesBuffer = json_decode($this->GetBuffer('Variables'), true);
            foreach ($result as $key => $value) {
                if (array_key_exists($key, $VariablesBuffer)) {
                    if ($VariablesBuffer[$key]['Active']) {
                        if ($this->GetIDForIdent($key) == false) {
                            $this->LogMessage($this->Translate('Variable deleted'), KL_ERROR);
                            continue;
                        }
                        switch ($key) {
                            case 'internalStorageFreeSpace':
                            case 'internalStorageTotalSpace':
                            case 'externalStorageFreeSpace':
                            case 'externalStorageTotalSpace':
                                $this->SetValue($key, round($value / (1024 * 1024 * 1024), 0));
                                break;
                            case 'ramUsedMemory':
                            case 'ramFreeMemory':
                            case 'ramTotalMemory':
                            case 'appUsedMemory':
                            case 'appFreeMemory':
                            case 'appTotalMemory':
                                $this->SetValue($key, round($value / (1024 * 1024), 0));
                                break;
                            case 'sensorInfo':
                                break;
                            default:
                                $this->SetValue($key, $value);
                                break;
                        }
                    }
                } else {
                    $this->LogMessage($this->Translate('Variable (' . $key . ') not defined, contact module devleoper'), KL_WARNING);
                }
            }
        } else {
            $this->SendDebug('No Data', 'Android Device offline?', 0);
        }
    }

    public function setBooleanSetting(string $Key, bool $Value)
    {
        $Value = $Value ? 'true' : 'false';
        $result = $this->sendRequest('?cmd=setBooleanSetting&key=' . $Key . '&value=' . $Value);
        return $this->checkRequest($result);
    }

    public function setStringSetting(string $Key, string $Value)
    {
        $result = $this->sendRequest('?cmd=setStringSetting&key=' . $Key . '&value=' . $Value);
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
                $this->toForeground();
                break;
            case 'Apps':
                $AppsListString = $this->ReadPropertyString('Apps');
                $Apps = json_decode($AppsListString);
                $App = $Apps[$Value - 1];
                $this->startApplication($App->Package);
                break;
            case 'maintenanceMode':
                $this->maintenanceMode($Value);
                break;
            case 'setOverlayMessage':
                $this->setOverlayMessage($Value);
                break;
            case 'DeviceControl':
                switch ($Value) {
                    case 0:
                        $this->shutdownDevice();
                        break;
                    case 1:
                        $this->rebootDevice();
                        break;
                }
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

    private function registerVariableProfiles()
    {
        //Shutdown / Reboot
        $Associations = [];
        $Associations[] = [0, $this->Translate('Shutdown'), '', -1];
        $Associations[] = [1, $this->Translate('Reboot'), '', -1];
        $this->RegisterProfileIntegerEx('FKB.DeviceControl', 'Remote', '', '', $Associations);

        // Yes / No
        $Associations = [];
        $Associations[] = [true, $this->Translate('Yes'), '', 0x00FF00];
        $Associations[] = [false, $this->Translate('No'), '', 0xFF0000];
        $this->RegisterProfileBooleanEx('FKB.YesNo', 'Information', '', '', $Associations);

        // Set
        $Associations = [];
        $Associations[] = [0, $this->Translate('Set'), '', -1];
        $this->RegisterProfileIntegerEx('FKB.Set', 'Remote', '', '', $Associations);

        // Space
        $this->RegisterProfileIntegerEx('FKB.SpaceMB', 'Graph', '', ' MB', []);
        $this->RegisterProfileIntegerEx('FKB.SpaceGB', 'Graph', '', ' GB', []);

        // Resolution
        $this->RegisterProfileIntegerEx('FKB.Resolution', 'TV', '', ' px', []);

        // TextToSpeech
        $Name = 'FKB.TextToSpeech';
        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, VARIABLETYPE_STRING);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != VARIABLETYPE_STRING) {
                throw new Exception('Variable profile type does not match for profile ' . $Name, E_USER_WARNING);
            }
        }
        IPS_SetVariableProfileIcon($Name, 'Speaker');

        // Information
        $Name = 'FKB.Information';
        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, VARIABLETYPE_STRING);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != VARIABLETYPE_STRING) {
                throw new Exception('Variable profile type does not match for profile ' . $Name, E_USER_WARNING);
            }
        }
        IPS_SetVariableProfileIcon($Name, 'Information');

        // Information
        $Name = 'FKB.Network';
        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, VARIABLETYPE_STRING);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != VARIABLETYPE_STRING) {
                throw new Exception('Variable profile type does not match for profile ' . $Name, E_USER_WARNING);
            }
        }
        IPS_SetVariableProfileIcon($Name, 'Network');
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
