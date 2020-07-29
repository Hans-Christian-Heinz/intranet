/*
 * Generiere Platzhalter, die beim Erstellen eines PDF-Dokuments in auf Überschriften verweisende Links konvertiert werden
 */

$(document).ready(function() {
    const link = $('input#generated_link');
    const link_text = $('input#link_text');
    const link_target = $('select#link_target');

    link_target.change(function() {
        link_text.val($(this).children("option:selected").html().trim());
        generateLink();
    });

    link_text.change(function() {
        generateLink();
    });

    function generateLink() {
        let text = link.val();
        let toReplaceTarget = text.substring(text.indexOf('(') + 1, text.indexOf(','));
        text = text.replace(toReplaceTarget, link_target.children("option:selected").val());
        let toReplaceText = text.substring(text.indexOf(',') + 2, text.indexOf(')'));
        text = text.replace(toReplaceText, link_text.val());

        link.val(text);
    }
});
