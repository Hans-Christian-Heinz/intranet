/*
 * Suche nach Benutzern (ajax)
 */

$(document).ready(function () {
    function search(e) {
        const search = e.target.value;
        const ddMenu = $('div#searchUserDropdownMenu');
        const url = e.target.getAttribute('data-url');
        axios.get('/admin/users/search/' + search)
            .then(response => response.data).then(data => {
                ddMenu.children().remove();
                data.forEach(d => {
                    ddMenu.append($('<a class="dropdown-item" href="' + url.replace("_id", d.id) + '">' + d.full_name + '</a>'));
                    ddMenu.append($('<div class="dropdown-divider"></div>'));
                });
        });

        $('button#searchUserDropdown').dropdown("show");
    }

    //todo figure out, which method and parameters to use here
    const throttledSearch = _.debounce(search, 50);

    $('input[type=text].searchUser').on('input', throttledSearch);
});
