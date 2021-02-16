/*
 * Einige Methoden zur Benutzerfreundlichkeit:
 *
 * remember which tab is supposed to be opened
 * Im Formular zum Hinzufügen von Bildern (addImageModal) soll das ausgewählte Bild angezeigt werden
 * In der Auflistung aller Nachrichten (route user.nachrichten) sollen durch Knopfdruck alle Nachrichten zum Löschen markiert werden
 * Navigationsleiste von Dokumentationen und Anträgen: sauberes Verhalten bzgl. collpase
 */

$(document).ready(function() {
    //Navigationsleiste Doku und Antrag:
    //sehr umständlich, da die neu erwartete Gestaltung nicht dem ursprünglichen Ansatz entspricht
    //ggf. vllt. sogar sinnvoller, das Formular von vorne neu zu erstellen.
    let coll = $('.navigation-collapse');
    //Wenn ein Unterabschnitt aufgeklappt wird, werden alle anderen auf gleicher Ebene zugeklappt
    coll.on('show.bs.collapse', function() {
        const el = $(this);
        let list = $(this).closest('ul');
        let li = list.children('li');
        li.children('.navigation-collapse').filter(function() {
            return !$(this).is(el);
        }).collapse('hide');
    });
    //Wenn ein Unterabschnitt aufgeklappt wird, wird er als aktiv markiert
    coll.on('show.bs.collapse', function() {
        const active = $(this).prevAll('a.nav-link');
        active.addClass('active');
        let list = $(this).parent('li').siblings('li');
        list.children('a.nav-link').filter(function() {
            return !$(this).is(active);
        }).removeClass('active');
    });
    //Wenn ein Unterabschnitt aufgeklappt wurde, soll einer seiner Abschnitte ausgewählt werden
    coll.on('shown.bs.collapse', function(e) {
        //Problem bisher: Unterabschnitte werden wieder zugeklappt
        let list = $(this).children('ul').first();
        let li = list.children('li');
        let navlink = li.first().children('a.nav-link').first();
        li.each(function() {
            if ($(this).children('a.nav-link').first().hasClass('active')) {
                navlink = $(this).children('a.nav-link').first();
            }
        });

        if (navlink.attr('data-toggle') === 'collapse') {
            //$(navlink.attr('href')).collapse('show');
            $(navlink.attr('href')).trigger('shown.bs.collapse');
        }
        else {
            navlink.removeClass('active');
            navlink.click();
        }
        e.stopPropagation();
    });
    //Wenn ein Abschnit angezeigt wird, sollen alle Unterabschnitte auf der selben Ebene in der Navigationsleiste zugeklappt werden.
    $('a.navigationlink').on('shown.bs.tab', function() {
        let li = $(this).parent('li').siblings('li');
        li.each(function() {
            $($(this).children('a').filter(function() {
                return $(this).attr('data-toggle') === 'collapse';
            }).attr('href')).collapse('hide');
        });

        $(this).parents('.collapse').collapse('show');
    });

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
    $('.nav-tabs > li > a.navigationlink').on('shown.bs.tab', function() {
        //Stelle sicher, dass alle angezeigten Überschriften breit genug sind
        //sectionHeadingsWidth();

        let link_id = $(this).attr('id');
        /*let name = link_id.substr(0, link_id.indexOf('_tab'));
        let parent_id = $(this).closest('ul.nav').attr('id');

        //Speichere den geöffneten Abschnitt in einem cookie
        document.cookie = 'TAB_' + parent_id + '=' + link_id + '; ' + 3600000;*/

        document.cookie = 'TAB=' + link_id + '; ' + 3600000;

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
    //Wenn ein Unterabschnitt geöffnet wird: Im Versionenvergleich denselben Unterabschnitt der anderen Version öffnen
    coll.on('shown.bs.collapse', function() {
        let id = $(this).attr('id');
        if (id.startsWith('v0')) {
            $('#' + id.replace('v0', 'v1')).collapse('show');
        }
        if (id.startsWith('v1')) {
            $('#' + id.replace('v1', 'v0')).collapse('show');
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

    //auto-select justify for the tinymce-editor
    $('button.tox-tbtn').each(function() {
        if ($(this).attr('aria-label') === 'Justify' && $(this).attr('title') === 'Justify') {
            $(this).attr('aria-pressed', 'true');
        }
    });

    //In der Navigationsleiste von Antrag und Dokumentation sind manche Überschriften bzw. Tabs nicht breit genug
    //Keine CSS-Lösung gefunden => überprüfe: Overflow vorhanden? Wenn ja: Breiter machen
    //Methode funktioniert nur für Tabs bzw. Überschriften, die im Moment angezeigt werden (andere haben display:none)
    /*function sectionHeadingsWidth() {
        const headings = $('ul.scrollnav').find('li.nav-item');
        headings.each(function() {
            let sw = $(this).prop('scrollWidth');
            if (0 < sw && $(this).prop('offsetWidth') < sw) {
                $(this).css('min-width', sw + 15);
            }
        });
    }*/

    //Wenn die Validierung eines Feldes fehlschägt (Projektantrag oder -dokumentation), hebe die entsprechenden Überschriften hervor
    function showErrors() {
        //Bei den Abschnitte Termin und Titel stimmen Fehlermeldungsname und Abschnittname nicht überein.
        if ($('input#start').hasClass('is-invalid') || $('input#end').hasClass('is-invalid')) {
            $('a#deadline_tab').addClass('fehler');
        }
        if ($('input#shortTitle').hasClass('is-invalid') || $('input#longTitle').hasClass('is-invalid')) {
            $('a#title_tab').addClass('fehler');
        }
        if ($('input#planung_input').hasClass('is-invalid') || $('input#entwurf_input').hasClass('is-invalid')
            || $('input#implementierung_input').hasClass('is-invalid') || $('input#test_input').hasClass('is-invalid')
            || $('input#abnahme_input').hasClass('is-invalid')) {
            $('a#soll_ist_vgl_tab').addClass('fehler');
        }


        //alte Gestaltung; einfacher
        /*let tabpane = $('a.nav-link.fehler').parents('.tab-pane');
        tabpane.each(function() {
            let link = $(this).attr('aria-labelledby');
            $('a#' + link).addClass('fehler');
        });*/

        //neue Gestaltung
        let collapse = $('a.nav-link.fehler').parents('.collapse');
        collapse.each(function() {
            let link = $(this).siblings('a.nav-link');
            link.addClass('fehler');
        });
    }

    //get the tabs that are supposed to be open from the cookie
    /*function getOpenTabs() {
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
    }*/

    function getOpenTabNew() {
        const cookieArray = document.cookie.split(';');
        let tab = '';
        for (let i = 0; i < cookieArray.length; i++) {
            const cookie = cookieArray[i];
            const end = cookie.indexOf('=');
            if (end === -1) {
                continue;
            }
            if (cookie.substring(0, end).trim() === 'TAB') {
                tab = (cookie.substr(end + 1));
                break;
            }
        }

        return tab;
    }

    //Löschen von Kommentaren: Asynchron
    $('a.deleteComment').click(deleteComment);
    $('a.answerComment').click(showAnswerForm);
    $( document ).on( "change", ":checkbox.acknowledge-comment", function (e) {
        let target = e.originalEvent.target;
        axios.patch(target.dataset.url, {
            acknowledge: target.checked
        })
            .then(resp => {
                if (!resp.data) {
                    target.checked = !target.checked;
                }
            });
    });
    $(document).on("click", "a.restoreComment", e => {
        let target = e.originalEvent.target;
        e.originalEvent.preventDefault();
        axios.patch(target.getAttribute("href"))
            .then(resp => resp.data)
            .then(data => {
                $("div#showComment" + data.id).replaceWith(data.html);
                $('a#deleteComment' + data.id).click(deleteComment);
                $('a#answerComment' + data.id).click(showAnswerForm);
                $("form#formAnswer" + data.id).submit(submitAnswerForm);
            })
            .catch(error => {
                console.log(error);
                if(error.response)
                    console.log(error.response.data);
            });
    });

    function showAnswerForm(e) {
        e.preventDefault();
        let form = $(e.target.getAttribute("href"));
        switch(form.css("display")) {
            case "none":
                form.css("display", "block");
                let input = e.target.getAttribute("id").replace("Comment", "");
                document.getElementById(input).focus();
                break;
            case "block":
                form.css("display", "none");
                break;
        }
    }

    //Löschen von Kommentaren
    function deleteComment(e) {
        e.preventDefault();
        const parent = $(this).parent();
        axios.delete($(this).attr('href'))
            .then(response => response.data)
            .then(data => {
                if (data) {
                    parent.remove();
                }
            });
    }

    //Speichern von Kommentaren: Asynchron
    $('form.formAddComment').submit(submitAddForm);

    function submitAddForm(e) {
        e.preventDefault();

        $("small.comment-validation-error").remove();

        const form = $(this);
        const input = document.getElementById($(this).attr("id").replace("form", "").toLowerCase());
        $.ajax(form.attr('action'),{
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response) {
                    $(response.html).insertBefore(form);
                    input.value = "";
                    //Wichtig: Event-Listener hinzufügen
                    $('a#deleteComment' + response.id).click(deleteComment);
                    $('a#answerComment' + response.id).click(showAnswerForm);
                    $("form#formAnswer" + response.id).submit(submitAnswerForm);
                }
            },
            error: function (jqXHR) {
                input.value = "";
                let errors = jqXHR.responseJSON.errors;
                Object.values(errors).forEach(err => {
                    let res = document.createElement("small");
                    res.classList.add("text-danger", "comment-validation-error", "text-right", "w-100", "text-small");
                    res.innerText = err;
                    // e.target.appendChild(res);
                    input.parentNode.appendChild(res);
                });
            }
        });
    }

    $('form.formAnswer').submit(submitAnswerForm);

    function submitAnswerForm(e) {
        e.preventDefault();
        const form = $(this);
        const container = document.getElementById($(this).data("container"))
        const input = document.getElementById($(this).attr("id").replace("form", "").toLowerCase());
        $.ajax(form.attr('action'),{
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response) {
                    container.innerHTML += response.html;
                    input.value = "";
                    //Wichtig: Event-Listener hinzufügen
                    $('a#deleteComment' + response.id).click(deleteComment);
                    $('a#answerComment' + response.id).click(showAnswerForm);
                    $("form#formAnswer" + response.id).submit(submitAnswerForm);
                }
            }
        });
    }

    $('a.showDeletedComments').click(e => {
        e.preventDefault();
        const container = document.getElementById(e.target.dataset.container);
        axios.get(e.target.getAttribute("href"))
            .then(resp => resp.data)
            .then(data => {
                container.innerHTML = data;
                $('a.deleteComment').click(deleteComment);
                $('a.answerComment').click(showAnswerForm);
                $("form.formAnswer").submit(submitAnswerForm);
            });
    })

    $('input#accept-rules').change(function(e) {
        let submit = $('button#accept-submit');
        if ($(e.target).is(':checked')) {
            submit.removeClass('btn-outline-primary disabled');
            submit.addClass('btn-primary');
        }
        else {
            submit.removeClass('btn-primary');
            submit.addClass('btn-outline-primary disabled');
        }
    });


    //sectionHeadingsWidth();
    showErrors();

    let tab = getOpenTabNew();
    if (tab) {
        $('a#' + tab).tab('show');
    }

    if ($('ul#proposalTab').length > 0) {
        $('textarea.sectiontext').css('height', $('ul#proposalTab').first().height() - 30);
    }
    else if ($('ul#documentationTab').length > 0) {
        $('textarea.sectiontext').css('height', $('ul#documentationTab').first().height() - 30);
    }
    else {
        $('textarea.sectiontext').css('height', "18rem");
    }

    $('[data-toggle="tooltip"]').tooltip();
});
