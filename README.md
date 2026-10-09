[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Version](https://img.shields.io/badge/Symcon%20Version-9.0%20%3E-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Check Style](https://github.com/Schnittcher/FullyKioskBrowser/actions/workflows/style.yml/badge.svg)](https://github.com/Schnittcher/FullyKioskBrowser/actions/workflows/style.yml)
[![Tests](https://github.com/Schnittcher/FullyKioskBrowser/actions/workflows/tests.yml/badge.svg)](https://github.com/Schnittcher/FullyKioskBrowser/actions/workflows/tests.yml)

# FullyKioskBrowser
Mit dieser Bibliothek lassen sich Android-Tablets, auf denen die App Fully Kiosk Browser läuft, aus IP-Symcon überwachen und fernsteuern.

## Inhaltsverzeichnis
- [FullyKioskBrowser](#fullykioskbrowser)
  - [Inhaltsverzeichnis](#inhaltsverzeichnis)
  - [1. Voraussetzungen](#1-voraussetzungen)
  - [2. Funktionsumfang](#2-funktionsumfang)
  - [3. Enthaltene Module](#3-enthaltene-module)
  - [4. Installation](#4-installation)
  - [5. Konfiguration in IP-Symcon](#5-konfiguration-in-ip-symcon)
  - [6. Spenden](#6-spenden)
  - [7. Lizenz](#7-lizenz)

## 1. Voraussetzungen

* mindestens IPS Version 9.0
* Fully Kiosk Browser mit PLUS-Lizenz auf dem Tablet
* In Fully Kiosk Browser aktivierter Remote Admin (Zugriff aus dem lokalen Netz) mit gesetztem Passwort
* Optional für den Empfang von Status und Ereignissen per MQTT: die MQTT-Integration in Fully Kiosk Browser (PLUS, Android 7 oder neuer) und ein MQTT-Server oder MQTT-Client in IP-Symcon

## 2. Funktionsumfang
* Anzeige des Gerätezustands in Variablen (Akku, Netzwerk, Speicher, Standort, Version, Bildschirm- und Kioskstatus und weitere)
* Steuerung von Bildschirm, Helligkeit, Bildschirmschoner, Kiosk- und Wartungsmodus
* Laden von Webseiten, Tabs und JavaScript im Fully Kiosk Browser
* Starten von Apps auf dem Tablet
* Sprachausgabe, Töne und Videos auf dem Tablet
* Screenshot und Kamerabild als Medienobjekt
* Lesen und Ändern der Einstellungen von Fully Kiosk Browser
* Optional: Status und Ereignisse kommen per MQTT vom Tablet, statt dass IP-Symcon es abfragt

Die einzelnen Funktionen stehen bei den Instanzen unter "Enthaltene Module" und in der README des jeweiligen Moduls.

## 3. Enthaltene Module

* [FKBDevice](FKBDevice/README.md)
  * Steuert und überwacht ein Tablet mit Fully Kiosk Browser.
  * Fragt das Tablet über die REST-Schnittstelle ab oder nimmt Status und Ereignisse per MQTT entgegen.
  * Die Variablen lassen sich einzeln aktivieren, die Befehle stehen als Funktionen für Skripte bereit.

## 4. Installation
Die Installation erfolgt über den IP-Symcon Module Store.

## 5. Konfiguration in IP-Symcon
Die Geräteinstanz für das FKB Device muss per Hand im Objektbaum erzeugt werden. Dort werden Host, Port und Passwort des Tablets eingetragen. Mit dem Button "Verbindung testen" lässt sich die Verbindung prüfen.

Für MQTT wird in der Instanz ein MQTT-Server oder MQTT-Client als übergeordnete Instanz ausgewählt. Ohne übergeordnete Instanz zeigt IP-Symcon den Hinweis, dass die Instanz eine übergeordnete Instanz benötigt. Die Instanz arbeitet dann trotzdem über die REST-Schnittstelle.

Die weitere Dokumentation bitte den einzelnen Modulen entnehmen.

## 6. Spenden
Dieses Modul ist für die nicht kommerzielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:

[![PayPal](https://img.shields.io/badge/PayPal-Spenden-00457C?logo=paypal&logoColor=white&style=for-the-badge)](https://www.paypal.com/donate?hosted_button_id=EK4JRP87XLSHW) [![Amazon Wunschzettel](https://img.shields.io/badge/Amazon-Wunschzettel-FF9900?logo=amazon&logoColor=white&style=for-the-badge)](https://www.amazon.de/hz/wishlist/ls/3JVWED9SZMDPK?ref_=wl_share)

## 7. Lizenz

[CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)
