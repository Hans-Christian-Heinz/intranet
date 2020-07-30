{{-- Inputs für einen Platzhalter für Tabellen --}}

<p class="text-justify">
    Fügen Sie Ihrem dem PDF-Dokument Tabellen hinzu: Grenzen Sie Tabellen durch ##TABLE(tabelleninhalt)()(fußzeile)## ab.
    Grenzen Sie Tabellenzeilen durch runde Klammern () ab.
    Grenzen Sie Kopfzeilen durch geschweifte Klammern {} ab. Kopieren Sie den Platzhalter an die Stelle, an der im PDF-Dokument eine
    Tabelle generiert werden soll.
</p>
<div class="my-3">
    <textarea class="insert_content" id="insert_table" placeholder="##TABLE(&#10;{header1 || header2}&#10;(cell1 || cell2)&#10;)##">
##TABLE(
{header1 || header2}
(cell1 || cell2)
)(Fußzeile)##
    </textarea>
</div>
