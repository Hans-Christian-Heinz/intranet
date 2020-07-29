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

    let tabs = getOpenTabs();
    tabs.forEach(tab => $('a#' + tab).tab('show'));
});
