/*
 * Einige Methoden zur Benutzerfreundlichkeit.
 * remember which tab is supposed to be opened
 * Im Formular zum Hinzufügen von Bildern (addImageModal) soll das ausgewählte Bild angezeigt werden
 * In der Auflistung aller Nachrichten (route user.nachrichten) sollen durch Knopfdruck alle Nachrichten zum Löschen markiert werden
 */

$(document).ready(function() {
    //Beim Auswählen eines Bildes addImageModal soll das Bild danach angezeigt sein
    $('input[type="radio"].radioImage').change(function() {
        // data-radio: id des img-tags _ dateipfad des bildes
        let radioData = $(this).attr('id').split('_');
        let img = $('img#img_' + radioData[1]);
        $('img#bild_gewaehlt').attr('src', img.attr('src'));
    });

    //when opening a tab, save it as a cookie
    //weiterhin: Im Formular zum Hinzufügen eines Bildes zur Dokumentation (addImageModal): stelle sicher, dass der
    //momentan geöffnete Abschnitt ausgewählt ist
    //Weiterhin: Beim Vergleich zweier Versionen eines Dokuments: Wenn ein Abschnitt auf der einen Version geöffnet wird,
    //soll er, wen möglich, auch auf der anderen geöffnet werden.
    $('.nav-tabs > li > a').on('shown.bs.tab', function() {
        //Stelle sicher, dass alle angezeigten Überschriften breit genug sind
        sectionHeadingsWidth();

        let link_id = $(this).attr('id');
        let name = link_id.substr(0, link_id.indexOf('_tab'));
        let parent_id = $(this).closest('ul.nav').attr('id');

        //Speichere den geöffneten Abschnitt in einem cookie
        document.cookie = 'TAB_' + parent_id + '=' + link_id + '; ' + 3600000;
        //addImageModal: der geöffnete Abschnitt ist ausgewählt (als Standard)
        $('option#section_image_' + name).attr("selected", "selected");
        //Beim Vergleich zweier Versionen: Öffne, wenn möglich, den selben Abschnitt der anderen Version
        if (link_id.startsWith('v0')) {
            $('a#' + link_id.replace('v0', 'v1')).tab('show');
        }
        if (link_id.startsWith('v1')) {
            $('a#' + link_id.replace('v1', 'v0')).tab('show');
        }
    });

    //Nachrichten: Wähle alle Nachrichten zum Löschen
    $('button#chooseAllMessages').click(function() {
        let inputs = $('input[type=checkbox].chooseMessage');
        //wahr genau dann, wenn bereits alle ausgewählt sind: Deselektiere sie
        if (inputs.prop('checked')) {
            inputs.prop('checked', false);
        }
        else {
            inputs.prop('checked', true);
        }
    });

    //In der Navigationsleiste von Antrag und Dokumentation sind manche Überschriften bzw. Tabs nicht breit genug
    //Keine CSS-Lösung gefunden => überprüfe: Overflow vorhanden? Wenn ja: Breiter machen
    //Methode funktioniert nur für Tabs bzw. Überschriften, die im Moment angezeigt werden (andere haben display:none)
    function sectionHeadingsWidth() {
        const headings = $('ul.scrollnav').find('li.nav-item');
        headings.each(function() {
            let sw = $(this).prop('scrollWidth');
            if (0 < sw && $(this).prop('offsetWidth') < sw) {
                $(this).css('min-width', sw + 15);
            }
        });
    }

    //Wenn die Validierung eines Feldes fehlschägt (Projektantrag oder -dokumentation), hebe die entsprechenden Überschriften hervor
    function showErrors() {
        //Bei den Abschnitte Termin und Titel stimmen Fehlermeldungsname und Abschnittname nicht überein.
        if ($('input#start').hasClass('is-invalid') || $('input#end').hasClass('is-invalid')) {
            $('a#deadline_tab').addClass('fehler');
        }
        if ($('input#shortTitle').hasClass('is-invalid') || $('input#longTitle').hasClass('is-invalid')) {
            $('a#title_tab').addClass('fehler');
        }

        let tabpane = $('a.nav-link.fehler').parents('.tab-pane');
        tabpane.each(function() {
            let link = $(this).attr('aria-labelledby');
            $('a#' + link).addClass('fehler');
        })
    }

    //get the tabs that are supposed to be open from the cookie
    function getOpenTabs() {
        const cookieArray = document.cookie.split(';');
        let tabs = [];
        for (let i = 0; i < cookieArray.length; i++) {
            const cookie = cookieArray[i];
            const end = cookie.indexOf('=');
            if (end === -1) {
                continue;
            }
            if (cookie.substring(0, end).trim().startsWith('TAB_')) {
                tabs.push(cookie.substr(end + 1));
            }
        }

        return tabs;
    }

    sectionHeadingsWidth();
    showErrors();

    let tabs = getOpenTabs();
    tabs.forEach(tab => $('a#' + tab).tab('show'));
});

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
