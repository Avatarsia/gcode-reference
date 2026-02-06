# PowerShell Script für G-code JSON Übersetzungen
# Übersetzt häufigste Befehle automatisch

$jsonPath = "data\marlin-commands.json"
$json = Get-Content $jsonPath -Raw -Encoding UTF8 | ConvertFrom-Json

# Deutsche Übersetzungen für häufigste G-codes
$translations = @{
    'G0' = 'Schnelle Positionierung (ohne Extrusion)'
    'G1' = 'Lineare Bewegung'
    'G2' = 'Kreisbewegung im Uhrzeigersinn (CW)'
    'G3' = 'Kreisbewegung gegen Uhrzeigersinn (CCW)'
    'G4' = 'Pause/Verzögerung'
    'G5' = 'Bézier-Kurve'
    'G10' = 'Werkzeugversatz (Retract)'
    'G11' = 'Wiederherstellen (Unretract)'
    'G12' = 'Düse reinigen'
    'G17' = 'XY-Ebene wählen'
    'G18' = 'XZ-Ebene wählen'
    'G19' = 'YZ-Ebene wählen'
    'G20' = 'Einheiten: Zoll'
    'G21' = 'Einheiten: Millimeter'
    'G26' = 'Mesh-Validierungsmuster'
    'G27' = 'Düse parken'
    'G28' = 'Automatisches Homing'
    'G29' = 'Bett-Nivellierung (Probing)'
    'G30' = 'Einzelpunkt-Probing'
    'G31' = 'Dock-Sonde'
    'G32' = 'Undock-Sonde'
    'G33' = 'Delta-Auto-Kalibrierung'
    'G34' = 'Z-Steppers Auto-Ausrichtung'
    'G35' = 'Tramming-Assistent'
    'G38.2' = 'Sonde Richtung Arbeitsstück bewegen'
    'G38.3' = 'Sonde Richtung Arbeitsstück bewegen'
    'G38.4' = 'Sonde weg vom Arbeitsstück bewegen'
    'G38.5' = 'Sonde weg vom Arbeitsstück bewegen'
    'G42' = 'Werkzeug-Offset-Modus'
    'G53' = 'Zu Maschinenkoordinaten wechseln'
    'G54' = 'Zu Arbeitskoordinaten System 1 wechseln'
    'G90' = 'Absolute Positionierung'
    'G91' = 'Relative Positionierung'
    'G92' = 'Position setzen'
    'M0' = 'Bedingungsloser Stop'
    'M1' = 'Bedingter Stop'
    'M3' = 'Spindel CW einschalten'
    'M4' = 'Spindel CCW einschalten'
    'M5' = 'Spindel ausschalten'
    'M17' = 'Motoren aktivieren'
    'M18' = 'Motoren deaktivieren'
    'M20' = 'SD-Karte auflisten'
    'M21' = 'SD-Karte initialisieren'
    'M22' = 'SD-Karte freigeben'
    'M23' = 'SD-Datei auswählen'
    'M24' = 'SD-Druck starten/fortsetzen'
    'M25' = 'SD-Druck pausieren'
    'M26' = 'SD-Position setzen'
    'M27' = 'SD-Druckstatus melden'
    'M28' = 'SD-Schreibmodus starten'
    'M29' = 'SD-Schreibmodus beenden'
    'M30' = 'Programm beenden und SD-Datei löschen'
    'M31' = 'Druckzeit ausgeben'
    'M32' = 'SD-Datei auswählen und starten'
    'M33' = 'Lange Dateinamen abrufen'
    'M34' = 'SD-Sortiermodus setzen'
    'M42' = 'Pin-Status setzen'
    'M43' = 'Pin-Debug'
    'M48' = 'Sonde Wiederholbarkeitstest'
    'M73' = 'Druckfortschritt setzen'
    'M75' = 'Drucktimer starten'
    'M76' = 'Drucktimer pausieren'
    'M77' = 'Drucktimer stoppen'
    'M78' = 'Druckzeit-Statistiken anzeigen'
    'M80' = 'Netzteil einschalten'
    'M81' = 'Netzteil ausschalten'
    'M82' = 'Extruder absolut-Modus'
    'M83' = 'Extruder relativ-Modus'
    'M84' = 'Motoren deaktivieren'
    'M85' = 'Inaktivitäts-Shutdown setzen'
    'M92' = 'Achsen-Schritte pro Einheit setzen'
    'M100' = 'Freien Speicher ausgeben'
    'M104' = 'Hotend-Temperatur setzen'
    'M105' = 'Temperaturen abfragen'
    'M106' = 'Lüfter einschalten'
    'M107' = 'Lüfter ausschalten'
    'M108' = 'Warten abbrechen'
    'M109' = 'Auf Hotend-Temperatur warten'
    'M110' = 'Zeilennummer setzen'
    'M111' = 'Debug-Level setzen'
    'M112' = 'Not-Aus'
    'M113' = 'Host-Keepalive setzen'
    'M114' = 'Aktuelle Position abfragen'
    'M115' = 'Firmware-Info abfragen'
    'M117' = 'LCD-Nachricht setzen'
    'M118' = 'Serielle Nachricht ausgeben'
    'M119' = 'Endstop-Status abfragen'
    'M120' = 'Endstops aktivieren'
    'M121' = 'Endstops deaktivieren'
    'M122' = 'TMC-Debug-Info'
    'M123' = 'Lüfter-Tachometer'
    'M125' = 'Park-Kopf'
    'M126' = 'Solenoid öffnen'
    'M127' = 'Solenoid schließen'
    'M128' = 'Solenoid-Status'
    'M140' = 'Bett-Temperatur setzen'
    'M141' = 'Kammertemperatur setzen'
    'M143' = 'Maximale Hotend-Temperatur setzen'
    'M145' = 'Material-Voreinstellungen setzen'
    'M149' = 'Temperatur-Einheiten setzen'
    'M150' = 'RGB(W) LED-Farbe setzen'
    'M154' = 'Positions-Auto-Report setzen'
    'M155' = 'Temperatur-Auto-Report setzen'
    'M163' = 'Mix-Faktor setzen'
    'M164' = 'Virtuellen Extruder speichern'
    'M165' = 'Mix setzen'
    'M166' = 'Gradient-Mix setzen'
    'M190' = 'Auf Bett-Temperatur warten'
    'M191' = 'Auf Kammertemperatur warten'
    'M200' = 'Filamentdurchmesser setzen'
    'M201' = 'Druck-Beschleunigung setzen'
    'M203' = 'Maximale Geschwindigkeit setzen'
    'M204' = 'Beschleunigungs-Einstellungen'
    'M205' = 'Erweiterte Einstellungen'
    'M206' = 'Home-Offset setzen'
    'M207' = 'Firmware-Retract setzen'
    'M208' = 'Firmware-Recover setzen'
    'M209' = 'Auto-Retract aktivieren'
}

$count = 0
$skipped = 0

foreach ($cmd in $json.commands) {
    if ($translations.ContainsKey($cmd.id)) {
        # Stelle sicher dass desc Objekt existiert
        if (-not $cmd.desc) {
            $cmd | Add-Member -NotePropertyName 'desc' -NotePropertyValue @{} -Force
        }
        
        # Füge deutsche Übersetzung hinzu wenn nicht vorhanden
        if (-not $cmd.desc.de) {
            $cmd.desc | Add-Member -NotePropertyName 'de' -NotePropertyValue $translations[$cmd.id] -Force
            $count++
            Write-Host "✓ $($cmd.id): $($translations[$cmd.id])" -ForegroundColor Green
        } else {
            $skipped++
        }
    }
}

# JSON speichern
$json | ConvertTo-Json -Depth 20 | Set-Content $jsonPath -Encoding UTF8

Write-Host "`n✅ Fertig!" -ForegroundColor Green
Write-Host "Übersetzt: $count Befehle" -ForegroundColor Cyan
Write-Host "Übersprungen (bereits DE): $skipped" -ForegroundColor Yellow
Write-Host "Gesamt: $($json.commands.Count) Befehle im JSON" -ForegroundColor Cyan
