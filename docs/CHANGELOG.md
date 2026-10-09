# Changelog

## 09.10.2026 - Version 2.0

### Neu
- Optionaler MQTT-Parent (MQTT-Server oder -Client): Das Tablet meldet Status und Ereignisse selbst, die Abfrage per REST dient dann nur noch als Rückfallebene.
- Achtung: Ohne MQTT-Parent zeigt die Instanz den Hinweis "benötigt eine übergeordnete Instanz". Die Instanz funktioniert trotzdem per REST.
- Neue Variablen "Letztes Ereignis", "Daten des letzten Ereignisses" und "Zeit des letzten Ereignisses" (aus den Fully-Ereignissen per MQTT, die Daten enthalten zum Beispiel die neue URL).
- Neue Variablen für Lautstärke (Musik bedienbar), Datenzähler, letzte Benutzereingabe, Standortgenauigkeit, Hotspot, Anzeigestatus und weitere Werte des Tablets.
- Die Bildschirmhelligkeit lässt sich jetzt über einen Schieberegler einstellen.
- Neue Funktionen: FKB_forceSleep, FKB_triggerMotion, FKB_restartApp, FKB_exitApp, FKB_injectJavascript, FKB_refreshTab, FKB_focusTab, FKB_closeTab, FKB_clearCache, FKB_clearWebstorage, FKB_clearCookies, FKB_resetWebview, FKB_stopTextToSpeech, FKB_stopVideo, FKB_listSettings, FKB_getScreenshot, FKB_getCamshot, FKB_testConnection.
- Neue Funktion FKB_textToSpeechEx: Text sprechen mit Sprache und Warteschlange.
- Optional HTTPS für die Verbindung zum Tablet.
- Button "Verbindung testen" im Konfigurationsformular.
- Variablen nutzen die neuen Darstellungen von IP-Symcon 9. Achtung: Die bisherigen Profile FKB.* werden nicht mehr verwendet und können nach dem Update gelöscht werden.

### Fixes
- Das Passwort erscheint nicht mehr im Debug-Fenster.
- Ist das Tablet nicht erreichbar, blockiert IP-Symcon nicht mehr bis zu einer Minute. Die Instanz zeigt dann einen Fehlerstatus.
- Die Variable "In den Hintergrund" wird jetzt angelegt.
- Der Standortanbieter wird jetzt angezeigt (die Variable war bisher immer leer).
- URLs, Texte und Schalter (zum Beispiel bei playSound, playVideo und loadApkFile) werden korrekt übergeben.
- Mehrere Tablets teilen sich nicht mehr die Auswahl der Apps. (Profil wurde immer überschrieben)
- Unbekannte Werte vom Tablet erzeugen keine Warnungen mehr im Meldungsfenster.
- Funktionen melden jetzt zuverlässig Erfolg oder Fehler zurück.
