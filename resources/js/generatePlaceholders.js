/*
 * Generiere Platzhalter, die beim Erstellen eines PDF-Dokuments durch Bilder, Tabelle, Listen oder Links ersetzt werden
 */

$(document).ready(function() {
    const link_placeholder = $('input#insert_placeholder');
    const link_text = $('input#insert_link_text');
    const link_target = $('select#insert_link_target');
    const insert_table = $('textarea#insert_table');
    const list_type = $('select#insert_list_type');
    const list_content = $('textarea#insert_list_content');
    const list_placeholder = $('textarea#insert_list_placeholder');

    link_target.change(function() {
        link_text.val($(this).children("option:selected").html().trim());
        generateLink();
    });

    link_text.change(function() {
        generateLink();
    });

    list_type.change(function() {
        generateList();
    });

    list_content.change(function() {
        generateList();
    });

    $('button#generate_list_placeholder').click(function() {
        generateList();
    })

    function generateLink() {
        let text = "##LINK(" + link_target.children("option:selected").val() + ", " + link_text.val() + ")##";
        link_placeholder.val(text);
    }

    function generateList() {
        let text = "##LIST(" + list_type.children("option:selected").val() + ", \n" + list_content.val() + "\n)##";
        list_placeholder.val(text);
    }
});
