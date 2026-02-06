# Klipper Commands Builder
# Baut vollständige klipper-commands.json aus Klipper-Dokumentation

$commands = @()

# ============================================================================
# STANDARD G-CODE COMMANDS
# ============================================================================

$commands += @{
    id       = "M18"
    code     = "M18"
    category = "motor_control"
    desc     = @{
        en = "Disable motors"
        de = "Motoren deaktivieren"
    }
    params   = @()
    usage    = "M18"
    notes    = @{
        en = "Same as M84"
        de = "Identisch mit M84"
    }
}

$commands += @{
    id       = "M82"
    code     = "M82"
    category = "extruder"
    desc     = @{
        en = "Absolute extrusion mode"
        de = "Absoluter Extruder-Modus"
    }
    params   = @()
    usage    = "M82"
    notes    = @{
        en = "E coordinates are absolute"
        de = "E-Koordinaten sind absolut"
    }
}

$commands += @{
    id       = "M83"
    code     = "M83"
    category = "extruder"
    desc     = @{
        en = "Relative extrusion mode"
        de = "Relativer Extruder-Modus"
    }
    params   = @()
    usage    = "M83"
    notes    = @{
        en = "E coordinates are relative"
        de = "E-Koordinaten sind relativ"
    }
}

$commands += @{
    id       = "M84"
    code     = "M84"
    category = "motor_control"
    desc     = @{
        en = "Disable motors"
        de = "Motoren deaktivieren"
    }
    params   = @()
    usage    = "M84"
    notes    = @{
        en = "Same as M18"
        de = "Identisch mit M18"
    }
}

$commands += @{
    id       = "M104"
    code     = "M104"
    category = "temperature"
    desc     = @{
        en = "Set hotend temperature"
        de = "Hotend-Temperatur setzen"
    }
    params   = @(
        @{name = "T"; type = "int"; desc = @{en = "Tool index"; de = "Werkzeug-Index" } },
        @{name = "S"; type = "float"; desc = @{en = "Target temperature"; de = "Zieltemperatur" } }
    )
    usage    = "M104 S200 ; M104 T0 S210"
    notes    = @{
        en = "Does not wait for temperature"
        de = "Wartet nicht auf Temperatur"
    }
}

$commands += @{
    id       = "M105"
    code     = "M105"
    category = "temperature"
    desc     = @{
        en = "Get current temperature"
        de = "Aktuelle Temperatur abfragen"
    }
    params   = @()
    usage    = "M105"
    notes    = @{
        en = "Reports all heater temperatures"
        de = "Meldet alle Heizer-Temperaturen"
    }
}

$commands += @{
    id       = "M106"
    code     = "M106"
    category = "fan"
    desc     = @{
        en = "Set fan speed"
        de = "Lüftergeschwindigkeit setzen"
    }
    params   = @(
        @{name = "S"; type = "int"; desc = @{en = "Speed (0-255)"; de = "Geschwindigkeit (0-255)" } }
    )
    usage    = "M106 S255 ; M106 S128"
    notes    = @{
        en = "S255 is 100% speed"
        de = "S255 ist 100% Geschwindigkeit"
    }
}

$commands += @{
    id       = "M107"
    code     = "M107"
    category = "fan"
    desc     = @{
        en = "Turn fan off"
        de = "Lüfter ausschalten"
    }
    params   = @()
    usage    = "M107"
    notes    = @{
        en = "Same as M106 S0"
        de = "Identisch mit M106 S0"
    }
}

$commands += @{
    id       = "M109"
    code     = "M109"
    category = "temperature"
    desc     = @{
        en = "Set hotend temperature and wait"
        de = "Hotend-Temperatur setzen und warten"
    }
    params   = @(
        @{name = "T"; type = "int"; desc = @{en = "Tool index"; de = "Werkzeug-Index" } },
        @{name = "S"; type = "float"; desc = @{en = "Target temperature"; de = "Zieltemperatur" } }
    )
    usage    = "M109 S200"
    notes    = @{
        en = "Waits until target temperature is reached"
        de = "Wartet bis Zieltemperatur erreicht ist"
    }
}

$commands += @{
    id       = "M112"
    code     = "M112"
    category = "control"
    desc     = @{
        en = "Emergency stop"
        de = "Not-Aus"
    }
    params   = @()
    usage    = "M112"
    notes    = @{
        en = "Immediately stops all operations"
        de = "Stoppt sofort alle Operationen"
    }
}

$commands += @{
    id       = "M114"
    code     = "M114"
    category = "query"
    desc     = @{
        en = "Get current position"
        de = "Aktuelle Position abfragen"
    }
    params   = @()
    usage    = "M114"
    notes    = @{
        en = "Reports current X Y Z E coordinates"
        de = "Meldet aktuelle X Y Z E Koordinaten"
    }
}

$commands += @{
    id       = "M115"
    code     = "M115"
    category = "query"
    desc     = @{
        en = "Get firmware version"
        de = "Firmware-Version abfragen"
    }
    params   = @()
    usage    = "M115"
    notes    = @{
        en = "Reports Klipper version"
        de = "Meldet Klipper-Version"
    }
}

$commands += @{
    id       = "M140"
    code     = "M140"
    category = "temperature"
    desc     = @{
        en = "Set bed temperature"
        de = "Bett-Temperatur setzen"
    }
    params   = @(
        @{name = "S"; type = "float"; desc = @{en = "Target temperature"; de = "Zieltemperatur" } }
    )
    usage    = "M140 S60"
    notes    = @{
        en = "Does not wait for temperature"
        de = "Wartet nicht auf Temperatur"
    }
}

$commands += @{
    id       = "M190"
    code     = "M190"
    category = "temperature"
    desc     = @{
        en = "Set bed temperature and wait"
        de = "Bett-Temperatur setzen und warten"
    }
    params   = @(
        @{name = "S"; type = "float"; desc = @{en = "Target temperature"; de = "Zieltemperatur" } }
    )
    usage    = "M190 S60"
    notes    = @{
        en = "Waits until target temperature is reached"
        de = "Wartet bis Zieltemperatur erreicht ist"
    }
}

$commands += @{
    id       = "M204"
    code     = "M204"
    category = "settings"
    desc     = @{
        en = "Set acceleration"
        de = "Beschleunigung setzen"
    }
    params   = @(
        @{name = "S"; type = "float"; desc = @{en = "Acceleration value"; de = "Beschleunigungswert" } },
        @{name = "P"; type = "float"; desc = @{en = "Print acceleration"; de = "Druck-Beschleunigung" } },
        @{name = "T"; type = "float"; desc = @{en = "Travel acceleration"; de = "Verfahrbeschleunigung" } }
    )
    usage    = "M204 S3000 ; M204 P3000 T3000"
    notes    = @{
        en = "If S not specified, uses minimum of P and T"
        de = "Wenn S nicht angegeben, wird Minimum von P und T verwendet"
    }
}

$commands += @{
    id       = "M220"
    code     = "M220"
    category = "settings"
    desc     = @{
        en = "Set speed factor override"
        de = "Geschwindigkeitsfaktor-Überschreibung setzen"
    }
    params   = @(
        @{name = "S"; type = "int"; desc = @{en = "Percentage (100=normal)"; de = "Prozentsatz (100=normal)" } }
    )
    usage    = "M220 S50 ; M220 S150"
    notes    = @{
        en = "Affects feedrate only, not extrusion"
        de = "Betrifft nur Vorschub, nicht Extrusion"
    }
}

$commands += @{
    id       = "M221"
    code     = "M221"
    category = "settings"
    desc     = @{
        en = "Set extrusion factor override"
        de = "Extrusionsfaktor-Überschreibung setzen"
    }
    params   = @(
        @{name = "S"; type = "int"; desc = @{en = "Percentage (100=normal)"; de = "Prozentsatz (100=normal)" } }
    )
    usage    = "M221 S95 ; M221 S105"
    notes    = @{
        en = "Flow rate multiplier"
        de = "Durchfluss-Multiplikator"
    }
}

$commands += @{
    id       = "M400"
    code     = "M400"
    category = "control"
    desc     = @{
        en = "Wait for moves to finish"
        de = "Auf Bewegungsabschluss warten"
    }
    params   = @()
    usage    = "M400"
    notes    = @{
        en = "Waits for all pending moves to complete"
        de = "Wartet auf Abschluss aller ausstehenden Bewegungen"
    }
}

# ============================================================================
# KLIPPER EXTENDED COMMANDS
# ============================================================================

# Bed Mesh Commands
$commands += @{
    id       = "BED_MESH_CALIBRATE"
    code     = "BED_MESH_CALIBRATE"
    category = "bed_leveling"
    desc     = @{
        en = "Probe bed and create mesh"
        de = "Bett vermessen und Mesh erstellen"
    }
    params   = @(
        @{name = "PROFILE"; type = "string"; desc = @{en = "Profile name"; de = "Profilname" } },
        @{name = "METHOD"; type = "string"; desc = @{en = "manual or automatic"; de = "manuell oder automatisch" } },
        @{name = "ADAPTIVE"; type = "int"; desc = @{en = "1 for adaptive mesh"; de = "1 für adaptives Mesh" } }
    )
    usage    = "BED_MESH_CALIBRATE ; BED_MESH_CALIBRATE PROFILE=default ADAPTIVE=1"
    notes    = @{
        en = "Creates bed compensation mesh"
        de = "Erstellt Bett-Kompensations-Mesh"
    }
}

$commands += @{
    id       = "BED_MESH_CLEAR"
    code     = "BED_MESH_CLEAR"
    category = "bed_leveling"
    desc     = @{
        en = "Clear active bed mesh"
        de = "Aktives Bett-Mesh löschen"
    }
    params   = @()
    usage    = "BED_MESH_CLEAR"
    notes    = @{
        en = "Recommended in end-gcode"
        de = "Empfohlen im End-GCode"
    }
}

$commands += @{
    id       = "BED_MESH_PROFILE"
    code     = "BED_MESH_PROFILE"
    category = "bed_leveling"
    desc     = @{
        en = "Manage bed mesh profiles"
        de = "Bett-Mesh-Profile verwalten"
    }
    params   = @(
        @{name = "LOAD"; type = "string"; desc = @{en = "Profile to load"; de = "Zu ladendes Profil" } },
        @{name = "SAVE"; type = "string"; desc = @{en = "Profile to save"; de = "Zu speicherndes Profil" } },
        @{name = "REMOVE"; type = "string"; desc = @{en = "Profile to remove"; de = "Zu entfernendes Profil" } }
    )
    usage    = "BED_MESH_PROFILE LOAD=default ; BED_MESH_PROFILE SAVE=test"
    notes    = @{
        en = "Must run SAVE_CONFIG after SAVE/REMOVE"
        de = "SAVE_CONFIG nach SAVE/REMOVE ausführen"
    }
}

# Probe Commands
$commands += @{
    id       = "PROBE"
    code     = "PROBE"
    category = "probing"
    desc     = @{
        en = "Probe single point at current XY"
        de = "Einzelpunkt an aktueller XY-Position messen"
    }
    params   = @()
    usage    = "PROBE"
    notes    = @{
        en = "Reports Z height"
        de = "Meldet Z-Höhe"
    }
}

$commands += @{
    id       = "PROBE_ACCURACY"
    code     = "PROBE_ACCURACY"
    category = "probing"
    desc     = @{
        en = "Test probe repeatability"
        de = "Sonden-Wiederholgenauigkeit testen"
    }
    params   = @(
        @{name = "SAMPLES"; type = "int"; desc = @{en = "Number of samples"; de = "Anzahl Messungen" } }
    )
    usage    = "PROBE_ACCURACY SAMPLES=10"
    notes    = @{
        en = "Reports standard deviation"
        de = "Meldet Standardabweichung"
    }
}

$commands += @{
    id       = "PROBE_CALIBRATE"
    code     = "PROBE_CALIBRATE"
    category = "calibration"
    desc     = @{
        en = "Start probe Z-offset calibration"
        de = "Sonden Z-Offset Kalibrierung starten"
    }
    params   = @()
    usage    = "PROBE_CALIBRATE"
    notes    = @{
        en = "Interactive calibration wizard"
        de = "Interaktiver Kalibrierungs-Assistent"
    }
}

# PID Calibration
$commands += @{
    id       = "PID_CALIBRATE"
    code     = "PID_CALIBRATE"
    category = "calibration"
    desc     = @{
        en = "Auto-tune PID parameters"
        de = "PID-Parameter automatisch abstimmen"
    }
    params   = @(
        @{name = "HEATER"; type = "string"; desc = @{en = "Heater name"; de = "Heizer-Name" } },
        @{name = "TARGET"; type = "float"; desc = @{en = "Target temperature"; de = "Zieltemperatur" } }
    )
    usage    = "PID_CALIBRATE HEATER=extruder TARGET=210"
    notes    = @{
        en = "Run SAVE_CONFIG to save results"
        de = "SAVE_CONFIG ausführen zum Speichern"
    }
}

# Control Commands
$commands += @{
    id       = "PAUSE"
    code     = "PAUSE"
    category = "print_control"
    desc     = @{
        en = "Pause current print"
        de = "Aktuellen Druck pausieren"
    }
    params   = @()
    usage    = "PAUSE"
    notes    = @{
        en = "Captures position for resume"
        de = "Speichert Position für Wiederaufnahme"
    }
}

$commands += @{
    id       = "RESUME"
    code     = "RESUME"
    category = "print_control"
    desc     = @{
        en = "Resume paused print"
        de = "Pausierten Druck fortsetzen"
    }
    params   = @(
        @{name = "VELOCITY"; type = "float"; desc = @{en = "Return speed"; de = "Rückkehrgeschwindigkeit" } }
    )
    usage    = "RESUME ; RESUME VELOCITY=50"
    notes    = @{
        en = "Returns to saved position"
        de = "Kehrt zu gespeicherter Position zurück"
    }
}

$commands += $(continued in next screen...)

Write-Host "Building complete Klipper commands JSON with $($commands.Count) commands..." -ForegroundColor Cyan

# Continue with more commands in follow-up
