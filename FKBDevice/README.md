# FKBDevice
   Dieses Modul ermöglicht es, ein Android Tablet mit der installierten Fully Kiosk Browser App fernzusteuern.
     
   ## Inhaltverzeichnis
   1. [Konfiguration](#1-konfiguration)
   2. [Funktionen](#2-funktionen)
   
   ## 1. Konfiguration
   
   Feld | Beschreibung
   ------------ | ----------------
   Host | IP-Adresse der Adnroid Tablets
   Port | Port der REST-API von der Fully Kiosk Browser App
   Passwort | Passwort für die REST-API
   Updateintervall | Aktualisierungsintervall in Sekunden
   App | In der Tabelle werden die Apps eingetragen, die über das Modul gestartet werden dürfen Beispiel: App: IPSView Package: brownson.ipsviewandroid
   
   ## 2. Funktionen

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

   ### FKB_stopSound(integer $InstanceID, integer $level, integer $stream)
   Mit dieser Funktion kann die Wiedergabe der Audiodatei gestoppt werden. Für die weiteren Parameter siehe die FKB-Doku.
 
   **Beispiel:**
   ```php
   FKB_stopSound(12345);
   ```

   ### FKB_playVideo(integer $InstanceID, string $url, bool $loop, bool $showControls, bool $exitOnTouch, $exitOnCompletion)
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
