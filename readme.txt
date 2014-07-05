head - ein moziloCMS Plugin
===========================

Beschreibung
------------

Überträgt den angegebenen Code in den Dokumentenkopf '<head></head>' der aktuellen Seite.
Zeichen, die zur mozilo-Syntax gehören, müssen im Code mit einem ^ (Hochdach) maskiert werden.

Beispiel
--------

    {head|
        <!-- head-Plugin -->
        <style type="text/css"> h1 ^{background-color: #f00;^} </style>
        <script type="text/javascript"> document.write("Hello World!") </script>
    }