/*
 * Popover zur Vorschau von Berichtsheften
 */

$(document).ready(function() {
    const wbPopover = $('tr.wb-popover');
    const wbTpl = '' +
            '<div class="popover" style="min-width: 100em; width: 100em;" role="tooltip">' +
                '<div class="arrow"></div>' +
                '<h3 class="popover-header text-center"></h3>' +
                '<div class="popover-body"></div>' +
            '</div>';

    wbPopover.popover({
        placement: "bottom",
        content: wbContent,
        html: true,
        template: wbTpl
    });
    wbPopover.hover(
        function() {
            $(this).popover('show');
        },
        function() {
            $(this).popover('hide');
        }
    );

    function wbContent() {
        const wb = JSON.parse($(this).attr('data-wb'));
        const wa = wb.work_activities ? wb.work_activities : '';
        const instr = wb.instructions ? wb.instructions : '';
        const school = wb.school ? wb.school : '';

        return '' +
            '<div class="d-flex justify-content-between">' +
                '<p class="w-25">Betriebliche Tätigkeiten:</p>' +
                '<p class="w-75">' + wa + "</p>" +
            "</div>" +
            '<div class="d-flex justify-content-between">' +
                '<p class="w-25">Unterweisungen:</p>' +
                '<p class="w-75">' + instr + "</p>" +
            "</div>" +
            '<div class="d-flex justify-content-between">' +
                '<p class="w-25">Berufsschule:</p>' +
                '<p class="w-75">' + school + "</p>" +
            "</div>";
    }
});
