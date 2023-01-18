# laravel-resources-optimisation

Was macht die Package ?

Diese Package dient dazu die Ausgabe an den Browser zu optimieren.
D.h. komprimieren der Ausgabe etc.

- Es wird geprüft, wann die Datei das letzte mal geändert wurde (wenn die Daten vorliegen), und sendet ggf. einen "304 Not Modified".
- Die Ausgabe wird mittels gzcompress verkleinert. Z.B. 144 kB auf 24.8 kB. Dies ist auch mit Assets möglich.
- Der eTag wird angepasst gesendet.
- Die Header werden auf optimierten Einstellungen gesendet.
