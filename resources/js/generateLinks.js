/*
 * Generiere Platzhalter, die beim Erstellen eines PDF-Dokuments in auf Überschriften verweisende Links konvertiert werden
 */

$(document).ready(function() {
    const link = $('input#generated_link');

    $('select#link_target').change(function() {
        let text = link.val();
        let toReplace = text.substring(text.indexOf('(') + 1, text.indexOf(','));
        text = text.replace(toReplace, $(this).children("option:selected").val());

        link.val(text);
    });

    $('input#link_text').change(function() {
        let text = link.val();
        let toReplace = text.substring(text.indexOf(',') + 2, text.indexOf(')'));
        text = text.replace(toReplace, $(this).val());

        link.val(text);
    });
});
