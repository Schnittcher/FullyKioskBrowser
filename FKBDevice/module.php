<?php

declare(strict_types=1);
class FKBDevice extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 2323);
        $this->RegisterPropertyString('Password', '');
        $this->RegisterPropertyInteger('Updateinterval', 10);

        $this->RegisterVariableString('deviceId', $this->Translate('Device ID'));
        $this->RegisterVariableString('deviceName', $this->Translate('Device Name'));
        $this->RegisterVariableFloat('altitude', $this->Translate('Altidude'));
        $this->RegisterVariableFloat('longitude', $this->Translate('Longitude'));
        $this->RegisterVariableFloat('latitude', $this->Translate('Latitude'));
        $this->RegisterVariableString('locationProvide', $this->Translate('Location Provide'));
        $this->RegisterVariableFloat('batteryLevel', $this->Translate('Battery'));
        $this->RegisterVariableFloat('batteryTemperature', $this->Translate('Battery Temperature'));
        $this->RegisterVariableBoolean('isPlugged', $this->Translate('isPlugged'), '~Switch');
        $this->RegisterVariableBoolean('plugged', $this->Translate('Plugged'), '~Switch');

        $this->RegisterVariableString('SSID', $this->Translate('SSID'));
        $this->RegisterVariableString('Mac', $this->Translate('MAC Address'));
        $this->RegisterVariableString('ip4', $this->Translate('IPv4'));
        $this->RegisterVariableString('ip6', $this->Translate('IPv6'));
        $this->RegisterVariableString('hostname4', $this->Translate('Hostname IPv4'));
        $this->RegisterVariableString('hostname6', $this->Translate('Hostname IPv6'));
        $this->RegisterVariableInteger('wifiSignalLevel', $this->Translate('Wifi Signal Level'));
        $this->RegisterVariableBoolean('isMobileDataEnabled', $this->Translate('Mobile Data'), '~Switch');

        $this->RegisterVariableInteger('screenOrientation', $this->Translate('Screen Orientation'));
        $this->RegisterVariableInteger('screenBrightness', $this->Translate('Screen Brightness'), '~Intensity.255');
        $this->RegisterVariableBoolean('screenLocked', $this->Translate('Screen Locked'), '~Switch');
        $this->RegisterVariableBoolean('screenOn', $this->Translate('Display'), '~Switch');
        $this->EnableAction('screenOn');
        $this->RegisterVariableBoolean('keyguardLocked', $this->Translate('Keyguard Locked'), '~Switch');

        $this->RegisterVariableString('locale', $this->Translate('Locale'));
        $this->RegisterVariableString('serial', $this->Translate('Serial'));
        $this->RegisterVariableString('version', $this->Translate('Version'));
        $this->RegisterVariableString('versionCode', $this->Translate('Version Code'));
        $this->RegisterVariableString('build', $this->Translate('Build'));
        $this->RegisterVariableString('model', $this->Translate('Model'));
        $this->RegisterVariableString('manufacturer', $this->Translate('Manufacturer'));
        $this->RegisterVariableString('androidVersion', $this->Translate('Android Version'));
        $this->RegisterVariableString('SDK', $this->Translate('SDK'));
        $this->RegisterVariableString('webviewUA', $this->Translate('webviewUA'));
        $this->RegisterVariableString('foreground', $this->Translate('Foreground'));
        $this->RegisterVariableBoolean('toForeground', $this->Translate('To Foreground'), '~Switch');
        $this->EnableAction('toForeground');
        $this->RegisterVariableString('foregroundActivity', $this->Translate('Foreground Activity'));
        $this->RegisterVariableInteger('motionDetectorStatus', $this->Translate('Motion Detector Status'));
        $this->RegisterVariableBoolean('isDeviceAdmin', $this->Translate('Device Admin'));
        $this->RegisterVariableBoolean('isDeviceOwner', $this->Translate('Device Owner'));
        $this->RegisterVariableInteger('internalStorageFreeSpace', $this->Translate('Internal Storage Free Space'));
        $this->RegisterVariableInteger('internalStorageTotalSpace', $this->Translate('Internal Storage Total Space'));
        $this->RegisterVariableInteger('ramUsedMemory', $this->Translate('Ram Used Memory'));
        $this->RegisterVariableInteger('ramFreeMemory', $this->Translate('Ram Free Memory'));
        $this->RegisterVariableInteger('ramTotalMemory', $this->Translate('Ram Total Memory'));

        $this->RegisterVariableInteger('appUsedMemory', $this->Translate('App Used Memory'));
        $this->RegisterVariableInteger('appFreeMemory', $this->Translate('App Free Memory'));
        $this->RegisterVariableInteger('appTotalMemory', $this->Translate('App Total Memory'));

        $this->RegisterVariableInteger('displayHeightPixels', $this->Translate('Display Height Pixels'));
        $this->RegisterVariableInteger('displayWidthPixels', $this->Translate('Display Width Pixels'));
        $this->RegisterVariableBoolean('isMenuOpen', $this->Translate('Menu Open'));
        $this->RegisterVariableString('topFragmentTag', $this->Translate('Top Fragment Tag'));
        $this->RegisterVariableBoolean('isInDaydream', $this->Translate('Daydream'), '~Switch');
        $this->EnableAction('isInDaydream');
        $this->RegisterVariableString('appStartTime', $this->Translate('App Start Time'));
        $this->RegisterVariableBoolean('isRooted', $this->Translate('Rooted'), '~Switch');
        $this->RegisterVariableBoolean('isLicensed', $this->Translate('Licensed'), '~Switch');
        $this->RegisterVariableBoolean('isInScreensaver', $this->Translate('Screensaver'), '~Switch');
        $this->EnableAction('isInScreensaver');
        $this->RegisterVariableBoolean('kioskLocked', $this->Translate('Kiosk Locked'), '~Switch');
        $this->RegisterVariableBoolean('isInForcedSleep', $this->Translate('In Forced Sleep'), '~Switch');
        $this->RegisterVariableBoolean('maintenanceMode', $this->Translate('Maintenance Mode'), '~Switch');
        $this->RegisterVariableBoolean('kioskMode', $this->Translate('Kiosk Mode'), '~Switch');
        $this->EnableAction('kioskMode');
        $this->RegisterVariableString('startUrl', $this->Translate('Start URL'));
        $this->RegisterVariableString('currentTabIndex', $this->Translate('Current TabIndex'));
        $this->RegisterVariableString('currentPageUrl', $this->Translate('Current Page URL'));

        $this->RegisterVariableString('textToSpeech', $this->Translate('Text to Speech'));
        $this->EnableAction('textToSpeech');

        $this->RegisterVariableInteger('Applications', $this->Translate('Applications'));

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