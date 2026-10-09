# FKBDevice
   Dieses Modul ermöglicht es, ein Android Tablet mit der installierten Fully Kiosk Browser App fernzusteuern.
     
   ## Inhaltverzeichnis
   1. [Konfiguration](#1-konfiguration)
   2. [Variablen](#2-variablen)
   3. [Funktionen](#3-funktionen)
   4. [Spenden](#4-spenden)
   5. [Lizenz](#5-lizenz)
   
   ## 1. Konfiguration
   
   Feld | Beschreibung
   ------------ | ----------------
   Host | IP-Adresse der Adnroid Tablets
   Port | Port der REST-API von der Fully Kiosk Browser App
   Passwort | Passwort für die REST-API
   HTTPS verwenden | Verbindung zum Tablet per HTTPS (das Zertifikat wird nicht geprüft)
   Updateintervall | Aktualisierungsintervall in Sekunden. Mit MQTT-Parent dient es nur als Rückfallebene (mindestens alle 300 Sekunden)
   Geräte-ID | MQTT: Geräte-ID des Tablets, leer = alle Geräte
   Variablen | Tabelle, in der Variablen aktiviert oder deaktiviert werden
   App | In der Tabelle werden die Apps eingetragen, die über das Modul gestartet werden dürfen Beispiel: App: IPSView Package: brownson.ipsviewandroid
   
   ## 2. Variablen

   Die Variablen werden in der Konfiguration einzeln aktiviert. Alle Variablen nutzen die Darstellungen von IP-Symcon 9. Der Ident entspricht dem Feldnamen aus den Geräteinformationen von Fully Kiosk Browser.

   Variable | Ident | Typ | Beschreibung
   ------------ | ------------ | ------------ | ----------------
   Display | screenOn | Boolean | Bildschirm ein/aus (bedienbar)
   Bildschirmhelligkeit | screenBrightness | Integer | 0 bis 255 (bedienbar)
   Bildschirmschoner | isInScreensaver | Boolean | Bildschirmschoner ein/aus (bedienbar)
   Daydream | isInDaydream | Boolean | Daydream ein/aus (bedienbar)
   Kioskmodus | kioskMode | Boolean | Kioskmodus ein/aus (bedienbar)
   Wartungsmodus | maintenanceMode | Boolean | Wartungsmodus ein/aus (bedienbar)
   In den Vordergrund / Hintergrund | toForeground, toBackground | Integer | Fully in den Vorder- oder Hintergrund (bedienbar)
   Apps | Apps | Integer | Startet eine der in der Konfiguration eingetragenen Apps (bedienbar)
   Gerätesteuerung | DeviceControl | Integer | Herunterfahren oder Neustart (bedienbar, nur gerootet)
   Vorlesen | textToSpeech | String | Text wird auf dem Tablet gesprochen (bedienbar)
   Überlagerungsnachricht | setOverlayMessage | String | Nachricht wird auf dem Tablet angezeigt (bedienbar)
   Lade Start URL / Lade URL | loadStartURL, loadURL | String | Lädt die Start-URL oder eine eingegebene URL (bedienbar)
   Letztes Ereignis | lastEvent | String | Letztes per MQTT gemeldetes Fully-Ereignis
   Daten des letzten Ereignisses | lastEventData | String | Zusatzdaten des Ereignisses als JSON (zum Beispiel die URL bei `onWebUrlChanged`), leer ohne Zusatzdaten
   Zeit des letzten Ereignisses | lastEventTime | Integer (Datum/Uhrzeit) | Zeitpunkt, zu dem das Ereignis bei IP-Symcon ankam
   Lautstärke Musik | volumeMusic | Integer (%) | Lautstärke des Musik-Streams, 0 bis 100 (bedienbar)
   Lautstärken | audioVolumes | String | Alle Lautstärken als JSON, Schlüssel ist die Stream-Nummer (3 = Musik)
   Datenzähler | rxBytesWifi, txBytesWifi, rxBytesMobile, txBytesMobile | Integer (MB) | Empfangene und gesendete Datenmenge, Wert -1 (nicht verfügbar) wird ignoriert
   Letzte Benutzereingabe | lastUserInteractionTime | Integer (Datum/Uhrzeit) | Zeitpunkt der letzten Eingabe am Tablet
   Weitere Anzeigewerte | displayState, displayLetterboxed, motionDetectorStarted, installerSource, systemSigned | verschieden | Zustand der Anzeige und der Installation
   Weitere | deviceId, deviceName, batteryLevel, batteryTemperature, SSID, ip4, ... | verschieden | Reine Anzeigewerte aus den Geräteinformationen (Speicher, Netzwerk, Version, Standort und weitere)

   Der Screenshot und das Kamerabild liegen nach dem Aufruf von `FKB_getScreenshot` und `FKB_getCamshot` als Medienobjekt unterhalb der Instanz.

   ### MQTT (optional)

   Die Instanz kann mit einem MQTT-Server oder MQTT-Client als übergeordnete Instanz verbunden werden. Das Tablet sendet dann seine Geräteinformationen und Ereignisse selbst, die Variablen werden sofort aktualisiert. Befehle laufen weiterhin über die REST-Schnittstelle. Die MQTT-Funktion in Fully Kiosk Browser (Plus) muss dazu aktiviert und auf den Broker gerichtet sein.

   ## 3. Funktionen

   ### FKB_screen(integer $InstanceID, bool $Value)
   Mit dieser Funktion kann das Display ein- bzw. ausgeschaltet werden.
 
   **Beispiel:**
   ```php
   FKB_screen(12345, true); //Einschalten
   FKB_screen(12345, false); //Auschalten
   ```

   ### FKB_screensaver(integer $InstanceID, bool $Value)
   Mit dieser Funktion kann der Bildschirmschoner ein- bzw. ausgeschaltet werden.
 
   **Beispiel:**
   ```php
   FKB_screensaver(12345, true); //Einschalten
   FKB_screensaver(12345, false); //Auschalten
   ```

   ### FKB_daydream(integer $InstanceID, bool $Value)
   Mit dieser Funktion kann Daydream ein- bzw. ausgeschaltet werden.
 
   **Beispiel:**
   ```php
   FKB_daydream(12345, true); //Einschalten
   FKB_daydream(12345, false); //Auschalten
   ```

   ### FKB_kioskMode(integer $InstanceID. bool $Value)
   Mit dieser Funktion kann der Kiosk Mode ein- bzw. ausgeschaltet werden.
 
   **Beispiel:**
   ```php
   FKB_kioskMode(12345, true); //Einschalten
   FKB_kioskMode(12345, false); //Auschalten
   ```

   ### FKB_startApplication(integer $InstanceID, string $Value)
   Mit dieser Funktion kann eine App auf dem Tablet gestartet werden.
 
   **Beispiel:**
   ```php
   FKB_startApplication(12345, 'ipsviewandroid'); //IPSView wird auf dem Tablet gestartet
   ```

   ### FKB_toForeground(integer $InstanceID)
   Mit dieser Funktion wird die Fully Kiosk Browser App in der Vordergrund geholt.
 
   **Beispiel:**
   ```php
   FKB_toForeground(12345); //IPSView wird auf dem Tablet gestartet
   ```

   ### FKB_popFragment(integer $InstanceID)
   ???
 
   **Beispiel:**
   ```php
   FKB_popFragment(12345); //???
   ```

   ### FKB_loadApkFile(integer $InstanceID, string $Value)
   Mit dieser Funktion kann eine neue App auf dem Tablet installiert werden.
 
   **Beispiel:**
   ```php
   FKB_loadApkFile(12345,'URL'); //Die App von der URL wird installiert
   ```

   ### FKB_maintenanceMode(integer $InstanceID, bool $Value)
   Mit dieser Funktion kann der Wartungsmodus ein- bzw. ausgeschaltet werden.
 
   **Beispiel:**
   ```php
   FKB_maintenanceMode(12345, true); //Wartungsmodus ein
   FKB_maintenanceMode(12345, false); //Wartungsmodus aus
   ```

   ### FKB_setOverlayMessage(integer $InstanceID, string $Value)
   Mit dieser Funktion kann eine Nachricht auf dem Tablet angezeigt werden.
 
   **Beispiel:**
   ```php
   FKB_setOverlayMessage(12345, true); //Wartungsmodus ein
   ```

   ### FKB_shutdownDevice(integer $InstanceID)
   Mit dieser Funktion kann das Gerät ausgeschaltet werden. (Nur wenn es gerootet ist!)
 
   **Beispiel:**
   ```php
   FKB_shutdownDevice(12345); //Gerät ausschalten
   ```

   ### FKB_rebootDevice(integer $InstanceID)
   Mit dieser Funktion kann das Gerät neugestartet werden. (Nur wenn es gerootet ist!)
 
   **Beispiel:**
   ```php
   FKB_rebootDevice(12345); //Gerät neustarten
   ```

   ### FKB_setAudioVolume(integer $InstanceID, integer $level, integer $stream)
   Mit dieser Funktion kann Lautstärke angepasst werden. Für die weiteren Parameter siehe die FKB-Doku.
 
   **Beispiel:**
   ```php
   FKB_setAudioVolume(12345,50 ,1);
   ```

   ### FKB_playSound(integer $InstanceID, string $url, bool $loop, integer $stream)
   Mit dieser Funktion kann eine Audiodatei gestartet werden. Für die weiteren Parameter siehe die FKB-Doku.
 
   **Beispiel:**
   ```php
   FKB_playSound(12345, 'URL zur Audiodatei',false,1);
   ```

   ### FKB_stopSound(integer $InstanceID)
   Mit dieser Funktion kann die Wiedergabe der Audiodatei gestoppt werden. Für die weiteren Parameter siehe die FKB-Doku.
 
   **Beispiel:**
   ```php
   FKB_stopSound(12345);
   ```

   ### FKB_playVideo(integer $InstanceID, string $url, bool $loop, bool $showControls, bool $exitOnTouch, bool $exitOnCompletion)
   Mit dieser Funktion kann ein Video gestartet werden. Für die weiteren Parameter siehe die FKB-Doku.
 
   **Beispiel:**
   ```php
   FKB_playVideo(12345, 'URL zum Video',false,true,true,true); //Das angegebene Video wird ein Mal abgespielt und auf Touch oder am Ende beendet.
   ```

   ### FKB_textToSpeech(integer $InstanceID, string $Value)
   Mit dieser Funktion kann ein Text auf dem Gerät wiedergegeben werden.
 
   **Beispiel:**
   ```php
   FKB_textToSpeech(12345, 'Hallo, IP-Symcon ist toll!'); //Der Text "Hallo, IP-Symcon ist toll!" wird auf dem Gerät wiedergegeben.
   ```

   ### FKB_setBooleanSetting(integer $InstanceID, string $Key, bool $Value)
   Mit dieser Funktion können boolesche Einstellungen auf dem Gerät verändert werden.
 
   **Beispiel:**
   ```php
   FKB_setBooleanSetting(12345, 'Key', true); //Einstellung "Key" wird auf true gesetzt
   ```

   ### FKB_setStringSetting(integer $InstanceID, string $Key, string $Value)
   Mit dieser Funktion können alle anderen Einstellungen auf dem Gerät verändert werden.
 
   **Beispiel:**
   ```php
   FKB_setStringSetting(12345, 'screenBrightness', '100'); //Helligkeit wird auf 100 gesetzt
   ```


   ### Weitere Funktionen

   Funktion | Beschreibung
   ------------ | ----------------
   FKB_forceSleep(integer $InstanceID) | Schickt das Gerät in den Standby
   FKB_triggerMotion(integer $InstanceID) | Löst eine Bewegungserkennung aus
   FKB_restartApp(integer $InstanceID) | Startet Fully Kiosk Browser neu
   FKB_exitApp(integer $InstanceID) | Beendet Fully Kiosk Browser
   FKB_loadStartURL(integer $InstanceID) | Lädt die Start-URL
   FKB_loadURL(integer $InstanceID, string $Value) | Lädt eine URL
   FKB_injectJavascript(integer $InstanceID, string $Code) | Führt JavaScript auf der Seite aus
   FKB_refreshTab(integer $InstanceID) | Lädt den aktuellen Tab neu
   FKB_focusTab(integer $InstanceID, integer $Tab) | Wechselt zu einem Tab
   FKB_closeTab(integer $InstanceID, integer $Tab) | Schließt einen Tab
   FKB_clearCache(integer $InstanceID) | Leert den Cache
   FKB_clearWebstorage(integer $InstanceID) | Leert den Webspeicher
   FKB_clearCookies(integer $InstanceID) | Löscht die Cookies
   FKB_resetWebview(integer $InstanceID) | Setzt die WebView zurück
   FKB_textToSpeechEx(integer $InstanceID, string $Value, string $Locale, bool $Queue) | Text sprechen mit Sprache (leer = Standard) und Warteschlange
   FKB_stopTextToSpeech(integer $InstanceID) | Stoppt die Sprachausgabe
   FKB_stopVideo(integer $InstanceID) | Stoppt die Videowiedergabe
   FKB_listSettings(integer $InstanceID) | Liefert alle Einstellungen des Tablets als JSON
   FKB_getScreenshot(integer $InstanceID) | Speichert einen Screenshot als Medienobjekt
   FKB_getCamshot(integer $InstanceID) | Speichert ein Kamerabild (Bewegungserkennung muss aktiv sein)
   FKB_testConnection(integer $InstanceID) | Prüft die Verbindung und liefert eine Meldung als Text

   Alle Funktionen liefern `true` bei Erfolg und `false` bei einem Fehler. Ausnahmen: `FKB_listSettings` und `FKB_testConnection` liefern einen Text.

   ## 4. Spenden

   Dieses Modul ist für die nicht kommerzielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:

   [![PayPal](https://img.shields.io/badge/PayPal-Spenden-00457C?logo=paypal&logoColor=white&style=for-the-badge)](https://www.paypal.com/donate?hosted_button_id=EK4JRP87XLSHW) [![Amazon Wunschzettel](https://img.shields.io/badge/Amazon-Wunschzettel-FF9900?logo=amazon&logoColor=white&style=for-the-badge)](https://www.amazon.de/hz/wishlist/ls/3JVWED9SZMDPK?ref_=wl_share)

   ## 5. Lizenz

   [CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)
