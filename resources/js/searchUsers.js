/*
 * Suche nach Benutzern (ajax)
 */

$(document).ready(function () {
    function search(e) {
        const search = e.target.value;
        const ddMenu = $('div#searchUserDropdownMenu');
        const url = e.target.getAttribute('data-url');
        axios.get('/admin/users/search/search/' + search)
            .then(response => response.data).then(data => {
                ddMenu.children().remove();
                //if (typeof data !== "undefined" && data.length > 0) {
                    data.forEach((d, index) => {
                        ddMenu.append($('<a class="dropdown-item" href="' + url.replace("_id", d.id) + '">' + d.full_name + '</a>'));
                        if (index !== data.length - 1) {
                            ddMenu.append($('<div class="dropdown-divider"></div>'));
                        }
                    });
                //}
        }).catch(function (error) {
            console.log(error);
        });

        $('button#searchUserDropdown').dropdown("show");
    }

    //debounce: die Methode wird erst aufgerufen, wenn 500ms nach dem letzten Aufruf kein neer Aufruf vorliegt.
    const debouncedSearch = _.debounce(search, 500);

    $('input[type=text]#searchUsers').on('input', debouncedSearch);
});
