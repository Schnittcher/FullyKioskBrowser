<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FKBDeviceTest extends TestCase
{
    private const MODULE_ID = '{D632C897-19CA-2750-A12A-8550700655C3}';
    private const PRESENTATION_VALUE = '{3319437D-7CDE-699D-750A-3C6A3841FA75}';
    private const PRESENTATION_SWITCH = '{60AE6B26-B3E2-BDB1-A3A1-BE232940664B}';
    private const PRESENTATION_SLIDER = '{6B9CAEEC-5958-C223-30F7-BD36569FC57A}';
    private const PRESENTATION_ENUMERATION = '{52D9E126-D7D2-2CBB-5E62-4CF7BA7C5D82}';
    private const PRESENTATION_INPUT = '{6F477326-1683-A2FD-D2E7-477F366ECB62}';
    private const PRESENTATION_DATE_TIME = '{497C4845-27FA-6E4F-AE37-5D951D3BDBF9}';

    // Idents der Version 1.x: Sie sind ein öffentlicher Vertrag (Archiv, Skripte, Visualisierung) und bleiben erhalten.
    // Nicht enthalten: "locationProvide" (Tippfehler, wird zu "locationProvider" migriert).
    private const LEGACY_IDENTS = [
        'deviceId', 'deviceName', 'altitude', 'longitude', 'latitude', 'batteryLevel', 'batteryTemperature', 'isPlugged',
        'plugged', 'SSID', 'BSSID', 'Mac', 'ip4', 'ip6', 'hostname4', 'hostname6', 'wifiSignalLevel', 'isMobileDataEnabled',
        'screenOrientation', 'screenBrightness', 'screenLocked', 'screenOn', 'keyguardLocked', 'locale', 'serial', 'version',
        'versionCode', 'build', 'model', 'manufacturer', 'androidVersion', 'SDK', 'webviewUA', 'foreground', 'toForeground',
        'foregroundActivity', 'motionDetectorStatus', 'isDeviceAdmin', 'isDeviceOwner', 'internalStorageFreeSpace',
        'internalStorageTotalSpace', 'ramUsedMemory', 'ramFreeMemory', 'ramTotalMemory', 'scopedStorage', 'appUsedMemory',
        'appFreeMemory', 'appTotalMemory', 'displayHeightPixels', 'displayWidthPixels', 'isMenuOpen', 'topFragmentTag',
        'isInDaydream', 'appStartTime', 'isRooted', 'isLicensed', 'isInScreensaver', 'kioskLocked', 'isInForcedSleep',
        'maintenanceMode', 'kioskMode', 'startUrl', 'currentTabIndex', 'currentPageUrl', 'textToSpeech', 'mqttConnected',
        'externalStorageFreeSpace', 'externalStorageTotalSpace', 'Apps', 'packageName', 'DeviceControl',
        'setOverlayMessage', 'loadStartURL', 'loadURL'
    ];

    private const ACTION_IDENTS = [
        'screenOn', 'screenBrightness', 'isInScreensaver', 'isInDaydream', 'kioskMode', 'maintenanceMode', 'toForeground',
        'toBackground', 'Apps', 'DeviceControl', 'textToSpeech', 'setOverlayMessage', 'loadStartURL', 'loadURL', 'volumeMusic'
    ];

    protected function setUp(): void
    {
        // Jeder Test startet mit einem leeren Kernel und lädt die Bibliothek neu
        IPS\Kernel::reset();
        IPS\ModuleLoader::loadLibrary(__DIR__ . '/../library.json');
        parent::setUp();
    }

    public function testApplyChangesCreatesAllVariablesWithNativePresentations(): void
    {
        $instanceID = $this->createInstance([]);

        $count = 0;
        foreach (IPS_GetChildrenIDs($instanceID) as $childID) {
            $variable = IPS_GetVariable($childID);
            $this->assertArrayHasKey('PRESENTATION', $variable['VariablePresentation'], IPS_GetObject($childID)['ObjectIdent']);
            $this->assertSame('', $variable['VariableProfile'], IPS_GetObject($childID)['ObjectIdent']);
            $count++;
        }
        $this->assertGreaterThan(70, $count);
    }

    public function testLegacyIdentsAreKept(): void
    {
        $instanceID = $this->createInstance([]);

        foreach (self::LEGACY_IDENTS as $ident) {
            $this->assertNotFalse($this->variableID($instanceID, $ident), 'Ident fehlt: ' . $ident);
        }
    }

    public function testVariableTypesOfExistingIdentsAreStable(): void
    {
        $instanceID = $this->createInstance([]);

        $types = [
            'deviceName'               => VARIABLETYPE_STRING, 'batteryLevel' => VARIABLETYPE_INTEGER, 'batteryTemperature' => VARIABLETYPE_FLOAT,
            'screenOn'                 => VARIABLETYPE_BOOLEAN, 'versionCode' => VARIABLETYPE_STRING, 'altitude' => VARIABLETYPE_FLOAT,
            'internalStorageFreeSpace' => VARIABLETYPE_INTEGER, 'Apps' => VARIABLETYPE_INTEGER, 'DeviceControl' => VARIABLETYPE_INTEGER,
            'textToSpeech'             => VARIABLETYPE_STRING, 'loadStartURL' => VARIABLETYPE_STRING, 'toForeground' => VARIABLETYPE_INTEGER
        ];
        foreach ($types as $ident => $type) {
            $this->assertSame($type, IPS_GetVariable($this->variableID($instanceID, $ident))['VariableType'], $ident);
        }
    }

    public function testOnlyControllableVariablesHaveAnAction(): void
    {
        $instanceID = $this->createInstance([]);

        foreach (self::LEGACY_IDENTS as $ident) {
            $hasAction = IPS_GetVariable($this->variableID($instanceID, $ident))['VariableAction'] === $instanceID;
            $this->assertSame(in_array($ident, self::ACTION_IDENTS, true), $hasAction, $ident);
        }
    }

    public function testPresentationsMatchTheKindOfVariable(): void
    {
        $instanceID = $this->createInstance([]);

        $this->assertSame(self::PRESENTATION_SWITCH, $this->presentation($instanceID, 'screenOn')['PRESENTATION']);
        $this->assertSame(self::PRESENTATION_VALUE, $this->presentation($instanceID, 'batteryLevel')['PRESENTATION']);
        $this->assertSame(' %', $this->presentation($instanceID, 'batteryLevel')['SUFFIX']);
        $this->assertSame(' GB', $this->presentation($instanceID, 'internalStorageFreeSpace')['SUFFIX']);
        $this->assertSame(self::PRESENTATION_SLIDER, $this->presentation($instanceID, 'screenBrightness')['PRESENTATION']);
        $this->assertSame(255, $this->presentation($instanceID, 'screenBrightness')['MAX']);
        $this->assertSame(self::PRESENTATION_INPUT, $this->presentation($instanceID, 'textToSpeech')['PRESENTATION']);
        $this->assertSame(self::PRESENTATION_ENUMERATION, $this->presentation($instanceID, 'DeviceControl')['PRESENTATION']);
    }

    public function testYesNoPresentationShowsBothValuesWithColors(): void
    {
        $instanceID = $this->createInstance([]);

        $options = json_decode($this->presentation($instanceID, 'isRooted')['OPTIONS'], true);

        $this->assertSame([false, true], array_column($options, 'Value'));
        $this->assertSame([0xFF0000, 0x00FF00], array_column($options, 'ColorValue'));
    }

    public function testRepeatedApplyChangesKeepsVariableIDs(): void
    {
        $instanceID = $this->createInstance(['Host' => '192.0.2.10']);
        $before = $this->variableIDs($instanceID);

        IPS_ApplyChanges($instanceID);
        IPS_ApplyChanges($instanceID);

        $this->assertSame($before, $this->variableIDs($instanceID));
    }

    public function testDeactivatedVariableIsRemovedAndOthersStay(): void
    {
        $instanceID = $this->createInstance(['Variables' => json_encode([['Ident' => 'batteryLevel', 'Active' => false]])]);

        $this->assertFalse($this->variableID($instanceID, 'batteryLevel'));
        $this->assertNotFalse($this->variableID($instanceID, 'screenOn'));
    }

    public function testMissingHostMeansInactive(): void
    {
        $instanceID = $this->createInstance(['Host' => '']);

        $this->assertSame(104, IPS_GetInstance($instanceID)['InstanceStatus']);
    }

    public function testConfiguredHostMeansActive(): void
    {
        $instanceID = $this->createInstance(['Host' => '192.0.2.10']);

        $this->assertSame(102, IPS_GetInstance($instanceID)['InstanceStatus']);
    }

    public function testOldIdentLocationProvideIsMigratedKeepingTheObject(): void
    {
        // Altzustand nachbauen: Version 1.x kannte nur "locationProvide"
        $instanceID = IPS_CreateInstance(self::MODULE_ID);
        IPS_DeleteVariable($this->variableID($instanceID, 'locationProvider'));
        $oldID = IPS_CreateVariable(VARIABLETYPE_STRING);
        IPS_SetParent($oldID, $instanceID);
        IPS_SetIdent($oldID, 'locationProvide');

        IPS_ApplyChanges($instanceID);
        IPS_ApplyChanges($instanceID);

        $this->assertSame($oldID, $this->variableID($instanceID, 'locationProvider'));
        $this->assertFalse($this->variableID($instanceID, 'locationProvide'));
    }

    public function testAppsSelectionBelongsToTheInstance(): void
    {
        $first = $this->createInstance(['Apps' => json_encode([['AppName' => 'Fully', 'Package' => 'de.ozerov.fully']])]);
        $second = $this->createInstance(['Apps' => json_encode([['AppName' => 'A', 'Package' => 'a.a'], ['AppName' => 'B', 'Package' => 'b.b']])]);

        $firstOptions = json_decode($this->presentation($first, 'Apps')['OPTIONS'], true);
        $secondOptions = json_decode($this->presentation($second, 'Apps')['OPTIONS'], true);

        $this->assertSame(['Fully'], array_column($firstOptions, 'Caption'));
        $this->assertSame([1, 2], array_column($secondOptions, 'Value'));
        $this->assertSame(['A', 'B'], array_column($secondOptions, 'Caption'));
    }

    public function testDeviceInfoFromMqttSetsConvertedValues(): void
    {
        $instanceID = $this->createInstance([]);

        $this->receive($instanceID, 'fully/deviceInfo/abc-123', [
            'deviceName'               => 'Wohnzimmer',
            'batteryLevel'             => 71,
            'batteryTemperature'       => 28,
            'isPlugged'                => true,
            'SSID'                     => '"Mein WLAN"',
            'versionCode'              => 1501,
            'internalStorageFreeSpace' => 5 * 1024 ** 3,
            'ramTotalMemory'           => 2048 * 1024 ** 2,
            'screenOn'                 => 'false',
            'sensorInfo'               => [['a' => 1]],
            'unbekanntesFeld'          => 1
        ]);

        $this->assertSame('Wohnzimmer', $this->value($instanceID, 'deviceName'));
        $this->assertSame(71, $this->value($instanceID, 'batteryLevel'));
        $this->assertSame(28.0, $this->value($instanceID, 'batteryTemperature'));
        $this->assertTrue($this->value($instanceID, 'isPlugged'));
        // Android liefert die SSID in Anführungszeichen
        $this->assertSame('Mein WLAN', $this->value($instanceID, 'SSID'));
        $this->assertSame('1501', $this->value($instanceID, 'versionCode'));
        $this->assertSame(5, $this->value($instanceID, 'internalStorageFreeSpace'));
        $this->assertSame(2048, $this->value($instanceID, 'ramTotalMemory'));
        $this->assertFalse($this->value($instanceID, 'screenOn'));
    }

    public function testEventFromMqttSetsLastEvent(): void
    {
        $instanceID = $this->createInstance([]);

        $this->receive($instanceID, 'fully/event/onScreensaverStart/abc-123', ['event' => 'onScreensaverStart']);

        $this->assertSame('onScreensaverStart', $this->value($instanceID, 'lastEvent'));
    }

    public function testEventDetailsAreStoredWithoutDeviceIdAndEventName(): void
    {
        $instanceID = $this->createInstance([]);
        $before = time();

        $this->receive($instanceID, 'fully/event/onWebUrlChanged/abc-123', [
            'url'      => 'https://www.example.org/start',
            'deviceId' => 'abc-123',
            'event'    => 'onWebUrlChanged'
        ]);

        $this->assertSame('onWebUrlChanged', $this->value($instanceID, 'lastEvent'));
        $this->assertSame('{"url":"https://www.example.org/start"}', $this->value($instanceID, 'lastEventData'));
        $this->assertGreaterThanOrEqual($before, $this->value($instanceID, 'lastEventTime'));
    }

    public function testEventWithoutDetailsClearsTheOldDetails(): void
    {
        $instanceID = $this->createInstance([]);
        $this->receive($instanceID, 'fully/event/onWebUrlChanged/abc-123', ['url' => 'https://www.example.org', 'event' => 'onWebUrlChanged']);

        $this->receive($instanceID, 'fully/event/screenOn/abc-123', ['deviceId' => 'abc-123', 'event' => 'screenOn']);

        $this->assertSame('screenOn', $this->value($instanceID, 'lastEvent'));
        $this->assertSame('', $this->value($instanceID, 'lastEventData'));
    }

    public function testAdditionalDeviceInfoFieldsAreConverted(): void
    {
        $instanceID = $this->createInstance([]);

        $this->receive($instanceID, 'fully/deviceInfo/abc-123', [
            'displayState'            => 2,
            'audioVolumes'            => [['4' => 83], ['8' => 26], ['5' => 68], ['3' => 28], ['1' => 71], [79], ['2' => 68]],
            'rxBytesWifi'             => 5 * 1024 ** 3,
            'txBytesWifi'             => 1024 ** 2,
            'rxBytesMobile'           => -1,
            'lastUserInteractionTime' => 1791578670035,
            'installerSource'         => 'com.android.vending',
            'systemSigned'            => false,
            'motionDetectorStarted'   => true
        ]);

        $this->assertSame(2, $this->value($instanceID, 'displayState'));
        $this->assertSame('{"0":79,"1":71,"2":68,"3":28,"4":83,"5":68,"8":26}', $this->value($instanceID, 'audioVolumes'));
        $this->assertSame(28, $this->value($instanceID, 'volumeMusic'));
        $this->assertSame(5120, $this->value($instanceID, 'rxBytesWifi'));
        $this->assertSame(1, $this->value($instanceID, 'txBytesWifi'));
        // -1 heißt: Zähler nicht verfügbar, der alte Wert bleibt
        $this->assertSame(0, $this->value($instanceID, 'rxBytesMobile'));
        // Fully liefert Millisekunden, die Variable braucht Sekunden
        $this->assertSame(1791578670, $this->value($instanceID, 'lastUserInteractionTime'));
        $this->assertSame('com.android.vending', $this->value($instanceID, 'installerSource'));
        $this->assertFalse($this->value($instanceID, 'systemSigned'));
        $this->assertTrue($this->value($instanceID, 'motionDetectorStarted'));
    }

    public function testMusicVolumeIsControllableAndTimesAreDateTimePresentations(): void
    {
        $instanceID = $this->createInstance([]);

        $this->assertSame($instanceID, IPS_GetVariable($this->variableID($instanceID, 'volumeMusic'))['VariableAction']);
        $this->assertSame(self::PRESENTATION_SLIDER, $this->presentation($instanceID, 'volumeMusic')['PRESENTATION']);
        $this->assertSame(100, $this->presentation($instanceID, 'volumeMusic')['MAX']);
        $this->assertSame(self::PRESENTATION_DATE_TIME, $this->presentation($instanceID, 'lastEventTime')['PRESENTATION']);
        $this->assertSame(self::PRESENTATION_DATE_TIME, $this->presentation($instanceID, 'lastUserInteractionTime')['PRESENTATION']);
        $this->assertNotSame($instanceID, IPS_GetVariable($this->variableID($instanceID, 'audioVolumes'))['VariableAction']);
    }

    public function testMqttMessagesOfOtherDevicesAreIgnoredWhenDeviceIdIsSet(): void
    {
        $instanceID = $this->createInstance(['MQTTDeviceId' => 'abc-123']);

        $this->receive($instanceID, 'fully/deviceInfo/andere-id', ['deviceName' => 'Fremd']);
        $this->receive($instanceID, 'fully/deviceInfo/abc-123', ['deviceName' => 'Eigenes']);

        $this->assertSame('Eigenes', $this->value($instanceID, 'deviceName'));
    }

    public function testMqttMessageForDeactivatedVariableIsIgnored(): void
    {
        $instanceID = $this->createInstance(['Variables' => json_encode([['Ident' => 'deviceName', 'Active' => false]])]);

        $this->receive($instanceID, 'fully/deviceInfo/abc-123', ['deviceName' => 'Tablet', 'batteryLevel' => 50]);

        $this->assertFalse($this->variableID($instanceID, 'deviceName'));
        $this->assertSame(50, $this->value($instanceID, 'batteryLevel'));
    }

    public function testBrokenMqttPayloadIsIgnored(): void
    {
        $instanceID = $this->createInstance([]);
        $module = IPS\InstanceManager::getInstanceInterface($instanceID);

        $module->ReceiveData(json_encode(['Topic' => 'fully/deviceInfo/abc-123', 'Payload' => bin2hex('kein json')]));
        $module->ReceiveData(json_encode(['Topic' => 'fully/deviceInfo/abc-123', 'Payload' => 'xyz']));
        $module->ReceiveData('nonsense');

        $this->assertSame('', $this->value($instanceID, 'deviceName'));
    }

    public function testRequestActionRejectsUnknownIdentAndValue(): void
    {
        $instanceID = $this->createInstance([]);
        $module = IPS\InstanceManager::getInstanceInterface($instanceID);

        $this->expectException(Exception::class);
        $module->RequestAction('batteryLevel', 50);
    }

    public function testDeviceControlRejectsUnknownValue(): void
    {
        $instanceID = $this->createInstance([]);
        $module = IPS\InstanceManager::getInstanceInterface($instanceID);

        $this->expectException(Exception::class);
        $module->RequestAction('DeviceControl', 7);
    }

    public function testConfigurationFormListsAllVariablesAndEndsWithDonationBlock(): void
    {
        $instanceID = $this->createInstance(['Variables' => json_encode([['Ident' => 'batteryLevel', 'Active' => false]])]);

        $form = json_decode(IPS_GetConfigurationForm($instanceID), true);

        $list = null;
        foreach ($form['elements'] as $element) {
            if (($element['name'] ?? '') === 'Variables') {
                $list = $element;
            }
        }
        $this->assertNotNull($list);
        $rows = array_column($list['values'], 'Active', 'Ident');
        foreach (self::LEGACY_IDENTS as $ident) {
            $this->assertArrayHasKey($ident, $rows, $ident);
        }
        $this->assertFalse($rows['batteryLevel']);
        $this->assertTrue($rows['screenOn']);

        $captions = array_column($form['actions'], 'caption');
        $this->assertContains('Donations', $captions);
        $this->assertSame('RowLayout', end($form['actions'])['type']);

        // Die Übersetzungen liefert Symcon aus der locale.json (die Stubs tun das nicht)
        $locale = json_decode(file_get_contents(__DIR__ . '/../FKBDevice/locale.json'), true);
        foreach ($captions as $caption) {
            if ($caption !== '') {
                $this->assertArrayHasKey($caption, $locale['translations']['de'], 'Keine Übersetzung für: ' . $caption);
            }
        }
    }

    public function testEveryStatusCodeOfTheModuleIsDeclaredInTheForm(): void
    {
        // Ein Statuscode ohne Eintrag unter "status" zeigt die Konsole als "Instanz hat einen ungültigen Statuscode" an
        $form = json_decode(file_get_contents(__DIR__ . '/../FKBDevice/form.json'), true);
        $declared = array_column($form['status'], 'icon', 'code');

        $connectionError = (new ReflectionClassConstant('FKBDevice', 'STATUS_CONNECTION_ERROR'))->getValue();

        $this->assertSame('active', $declared[IS_ACTIVE] ?? null);
        $this->assertSame('inactive', $declared[IS_INACTIVE] ?? null);
        $this->assertSame('error', $declared[$connectionError] ?? null);

        $locale = json_decode(file_get_contents(__DIR__ . '/../FKBDevice/locale.json'), true);
        foreach (array_column($form['status'], 'caption') as $caption) {
            $this->assertArrayHasKey($caption, $locale['translations']['de'], $caption);
        }
    }

    public function testPublicFunctionsHaveNoDefaultValues(): void
    {
        // Die von Symcon erzeugten FKB_-Funktionen kennen keine Standardwerte:
        // ein optionaler Parameter würde bestehende Skripte mit weniger Argumenten brechen.
        $class = new ReflectionClass('FKBDevice');
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== 'FKBDevice') {
                continue;
            }
            foreach ($method->getParameters() as $parameter) {
                $this->assertFalse($parameter->isOptional(), $method->getName() . '::' . $parameter->getName());
            }
        }
        $this->assertSame(1, (new ReflectionMethod('FKBDevice', 'textToSpeech'))->getNumberOfParameters());
    }

    private function createInstance(array $configuration): int
    {
        $instanceID = IPS_CreateInstance(self::MODULE_ID);
        IPS_SetConfiguration($instanceID, json_encode($configuration));
        IPS_ApplyChanges($instanceID);
        return $instanceID;
    }

    private function receive(int $instanceID, string $topic, array $payload): void
    {
        IPS\InstanceManager::getInstanceInterface($instanceID)->ReceiveData(json_encode([
            'DataID'  => '{7F7632D9-FA40-4F38-8DEA-C83CD4325A32}',
            'Topic'   => $topic,
            'Payload' => bin2hex(json_encode($payload))
        ]));
    }

    private function variableID(int $instanceID, string $ident): int|false
    {
        $variableID = @IPS_GetObjectIDByIdent($ident, $instanceID);
        return $variableID === false ? false : $variableID;
    }

    private function variableIDs(int $instanceID): array
    {
        $ids = [];
        foreach (IPS_GetChildrenIDs($instanceID) as $childID) {
            $ids[IPS_GetObject($childID)['ObjectIdent']] = $childID;
        }
        return $ids;
    }

    private function presentation(int $instanceID, string $ident): array
    {
        return IPS_GetVariable($this->variableID($instanceID, $ident))['VariablePresentation'];
    }

    private function value(int $instanceID, string $ident): mixed
    {
        return GetValue($this->variableID($instanceID, $ident));
    }
}
