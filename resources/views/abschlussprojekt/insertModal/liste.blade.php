{{-- Inputs für einen Platzhalter für Listen --}}

<p class="text-justify">
    Fügen Sie Ihrem dem PDF-Dokument Listen hinzu: Wählen Sie die AUfzählungsart, definieren sie den Listeninhalt, wobei
    Listenpunkte durch runde Klammern () abgegrenzt werden. Kopieren Sie den generierten Platzhalter an die Stelle, an der
    im PDF-Dokument die Liste eingefügt werden soll.
</p>
<div class="my-3">
    <label class="control-label" for="insert_list_type">Aufzählungsart</label>
    <select class="form-control" id="insert_list_type">
        <option selected value="unordered">Ungeordnet</option>
        <option value="1">1</option>
        <option value="a">a</option>
        <option value="A">A</option>
        <option value="I">I</option>
        <option value="i">i</option>
    </select>
</div>
<div class="my-3">
    <textarea class="insert_content" id="insert_list_content" placeholder="(list item 1)&#10;(list item 1)">
(list item 1)
(list item 2)
    </textarea>
</div>
<div class="my-3">
    <button type="button" class="btn btn-secondary float-right" id="generate_list_placeholder">Platzhalter generieren</button>
</div>
<div class="my-3">
    <textarea class="insert_content" id="insert_list_placeholder" disabled>
##LIST(unordered,
(list item 1)
(list item 2)
)##
    </textarea>
</div>
