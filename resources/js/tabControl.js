/*
 * remember which tab is supposed to be opened
 */

$(document).ready(function() {
    //when opening a tab, save it as a cookie
    $('.nav-tabs > li > a').on('shown.bs.tab', function() {
        let link_id = $(this).attr('id');
        let parent_id = $(this).closest('ul.nav').attr('id');
        document.cookie = 'TAB_' + parent_id + '=' + link_id + '; ' + 3600000;
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
