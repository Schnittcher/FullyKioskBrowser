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

        $this->registerVariableProfiles();

        $this->RegisterVariableString('deviceId', $this->Translate('Device ID'), 'FKB.Information');
        $this->RegisterVariableString('deviceName', $this->Translate('Devicename'), 'FKB.Information');
        $this->RegisterVariableFloat('altitude', $this->Translate('Altidude'));
        $this->RegisterVariableFloat('longitude', $this->Translate('Longitude'));
        $this->RegisterVariableFloat('latitude', $this->Translate('Latitude'));
        $this->RegisterVariableString('locationProvide', $this->Translate('Location Provide'), 'FKB.Information');
        $this->RegisterVariableInteger('batteryLevel', $this->Translate('Battery'), '~Battery.100');
        $this->RegisterVariableFloat('batteryTemperature', $this->Translate('Battery Temperature'), '~Temperature');
        $this->RegisterVariableBoolean('isPlugged', $this->Translate('isPlugged'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('plugged', $this->Translate('Plugged'), 'FKB.YesNo');

        $this->RegisterVariableString('SSID', $this->Translate('SSID'), 'FKB.Network');
        $this->RegisterVariableString('Mac', $this->Translate('MAC Address'), 'FKB.Network');
        $this->RegisterVariableString('ip4', $this->Translate('IPv4'), 'FKB.Network');
        $this->RegisterVariableString('ip6', $this->Translate('IPv6'), 'FKB.Network');
        $this->RegisterVariableString('hostname4', $this->Translate('Hostname IPv4'), 'FKB.Network');
        $this->RegisterVariableString('hostname6', $this->Translate('Hostname IPv6'), 'FKB.Network');
        $this->RegisterVariableInteger('wifiSignalLevel', $this->Translate('Wifi Signal Level'));
        $this->RegisterVariableBoolean('isMobileDataEnabled', $this->Translate('Mobile Data'), '~Switch');

        $this->RegisterVariableInteger('screenOrientation', $this->Translate('Screen Orientation'));
        $this->RegisterVariableInteger('screenBrightness', $this->Translate('Screen Brightness'), '~Intensity.255');
        $this->RegisterVariableBoolean('screenLocked', $this->Translate('Screen Locked'), '~Switch');
        $this->RegisterVariableBoolean('screenOn', $this->Translate('Display'), '~Switch');
        $this->EnableAction('screenOn');
        $this->RegisterVariableBoolean('keyguardLocked', $this->Translate('Keyguard Locked'), '~Switch');

        $this->RegisterVariableString('locale', $this->Translate('Locale'), 'FKB.Information');
        $this->RegisterVariableString('serial', $this->Translate('Serial'), 'FKB.Information');
        $this->RegisterVariableString('version', $this->Translate('Version'), 'FKB.Information');
        $this->RegisterVariableString('versionCode', $this->Translate('Version Code'), 'FKB.Information');
        $this->RegisterVariableString('build', $this->Translate('Build'), 'FKB.Information');
        $this->RegisterVariableString('model', $this->Translate('Model'), 'FKB.Information');
        $this->RegisterVariableString('manufacturer', $this->Translate('Manufacturer'), 'FKB.Information');
        $this->RegisterVariableString('androidVersion', $this->Translate('Android Version'), 'FKB.Information');
        $this->RegisterVariableString('SDK', $this->Translate('SDK'), 'FKB.Information');
        $this->RegisterVariableString('webviewUA', $this->Translate('webviewUA'), 'FKB.Information');
        $this->RegisterVariableString('foreground', $this->Translate('Foreground'), 'FKB.Information');
        $this->RegisterVariableInteger('toForeground', $this->Translate('To Foreground'), 'FKB.Set');
        $this->EnableAction('toForeground');
        $this->RegisterVariableString('foregroundActivity', $this->Translate('Foreground Activity'), 'FKB.Information');
        $this->RegisterVariableInteger('motionDetectorStatus', $this->Translate('Motion Detector Status'));
        $this->RegisterVariableBoolean('isDeviceAdmin', $this->Translate('Device Admin'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('isDeviceOwner', $this->Translate('Device Owner'), 'FKB.YesNo');
        $this->RegisterVariableInteger('internalStorageFreeSpace', $this->Translate('Internal Storage Free Space'), 'FKB.SpaceGB');
        $this->RegisterVariableInteger('internalStorageTotalSpace', $this->Translate('Internal Storage Total Space'), 'FKB.SpaceGB');
        $this->RegisterVariableInteger('ramUsedMemory', $this->Translate('Ram Used Memory'), 'FKB.SpaceMB');
        $this->RegisterVariableInteger('ramFreeMemory', $this->Translate('Ram Free Memory'), 'FKB.SpaceMB');
        $this->RegisterVariableInteger('ramTotalMemory', $this->Translate('Ram Total Memory'), 'FKB.SpaceMB');

        $this->RegisterVariableInteger('appUsedMemory', $this->Translate('App Used Memory'), 'FKB.SpaceMB');
        $this->RegisterVariableInteger('appFreeMemory', $this->Translate('App Free Memory'), 'FKB.SpaceMB');
        $this->RegisterVariableInteger('appTotalMemory', $this->Translate('App Total Memory'), 'FKB.SpaceMB');

        $this->RegisterVariableInteger('displayHeightPixels', $this->Translate('Display Height Pixels'), 'FKB.Resolution');
        $this->RegisterVariableInteger('displayWidthPixels', $this->Translate('Display Width Pixels'), 'FKB.Resolution');
        $this->RegisterVariableBoolean('isMenuOpen', $this->Translate('Menu Open'), 'FKB.YesNo');
        $this->RegisterVariableString('topFragmentTag', $this->Translate('Top Fragment Tag'), 'FKB.Information');
        $this->RegisterVariableBoolean('isInDaydream', $this->Translate('Daydream'), '~Switch');
        $this->EnableAction('isInDaydream');
        $this->RegisterVariableString('appStartTime', $this->Translate('App Start Time'), 'FKB.Information');
        $this->RegisterVariableBoolean('isRooted', $this->Translate('Rooted'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('isLicensed', $this->Translate('Licensed'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('isInScreensaver', $this->Translate('Screensaver'), '~Switch');
        $this->EnableAction('isInScreensaver');
        $this->RegisterVariableBoolean('kioskLocked', $this->Translate('Kiosk Locked'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('isInForcedSleep', $this->Translate('In Forced Sleep'), 'FKB.YesNo');
        $this->RegisterVariableBoolean('maintenanceMode', $this->Translate('Maintenance Mode'), '~Switch');
        $this->EnableAction('maintenanceMode');
        $this->RegisterVariableBoolean('kioskMode', $this->Translate('Kiosk Mode'), '~Switch');
        $this->EnableAction('kioskMode');
        $this->RegisterVariableString('startUrl', $this->Translate('Start URL'));
        $this->RegisterVariableString('currentTabIndex', $this->Translate('Current TabIndex'), 'FKB.Information');
        $this->RegisterVariableString('currentPageUrl', $this->Translate('Current Page URL'), 'FKB.Information');

        $this->RegisterVariableString('textToSpeech', $this->Translate('Text to Speech'), 'FKB.TextToSpeech');
        $this->EnableAction('textToSpeech');

        if (!IPS_VariableProfileExists('FKB.Apps')) {
            $this->RegisterProfileIntegerEx('FKB.Apps', 'Database', '', '', []);
        }
        $this->RegisterVariableInteger('Apps', $this->Translate('Apps'), 'FKB.Apps');
        $this->EnableAction('Apps');

        $this->RegisterVariableInteger('DeviceControl', $this->Translate('Device Control'), 'FKB.DeviceControl');
        $this->EnableAction('DeviceControl');

        $this->RegisterVariableString('setOverlayMessage', $this->Translate('Set Overlay Message'));
        $this->EnableAction('setOverlayMessage');

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

        foreach ($result as $key => $value) {
            switch ($key) {
                case 'internalStorageFreeSpace':
                case 'internalStorageTotalSpace':
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
                default:
                    $this->SetValue($key, $value);
                    break;
            }
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
