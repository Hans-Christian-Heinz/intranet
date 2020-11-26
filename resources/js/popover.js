/*
 * Popover zur Vorschau von Berichtsheften
 */

$(document).ready(function() {
    const wbPopover = $('tr.wb-popover');
    const wbTpl = '' +
            '<div class="popover" style="min-width: 100em; width: 100em; pointer-events: none;" role="tooltip">' +
                '<div class="arrow"></div>' +
                '<h3 class="popover-header text-center"></h3>' +
                '<div class="popover-body"></div>' +
            '</div>';

    wbPopover.popover({
        placement: "bottom",
        content: wbContent,
        html: true,
        template: wbTpl,
        trigger: "hover"
    });
    /*wbPopover.hover(
        function() {
            $(this).popover('show');
        },
        function() {
            $(this).popover('hide');
        }
    );*/

    function wbContent() {
        const wb = JSON.parse($(this).attr('data-wb'));
        const wa = wb.work_activities ? nl2br(wb.work_activities) : '';
        const instr = wb.instructions ? nl2br(wb.instructions) : '';
        const school = wb.school ? nl2br(wb.school) : '';

        return '' +
            '<div class="d-flex justify-content-between wb-popover-section">' +
                '<p class="w-25">Betriebliche Tätigkeiten:</p>' +
                '<p class="w-75">' + wa + "</p>" +
            "</div>" +
            '<div class="d-flex justify-content-between wb-popover-section">' +
                '<p class="w-25">Unterweisungen:</p>' +
                '<p class="w-75">' + instr + "</p>" +
            "</div>" +
            '<div class="d-flex justify-content-between wb-popover-section">' +
                '<p class="w-25">Berufsschule:</p>' +
                '<p class="w-75">' + school + "</p>" +
            "</div>";
    }

    //Wenn der Inhalt eines Abschnitts zu lange für die Vorschau ist
    wbPopover.on('shown.bs.popover', function() {
        let section = $('div.wb-popover-section');
        section.each(function() {
            //clientHeight < scrollHeight => overflow?
            if ($(this).prop("clientHeight") < $(this).prop("scrollHeight")) {
                $('<span class="mt-0 pt-0" style="margin-left: 25%; display: block;">...</span>').insertAfter($(this));
            }
        });
    });
});
