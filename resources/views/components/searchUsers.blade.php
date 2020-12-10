{{-- input zum asynchronen Suchen nach Benutzern, vgl. searchUsers.js --}}

<input class="form-control searchUser" data-url="{{ $url }}" placeholder="Benutzer suchen" type="text"/>
<div class="dropdown">
    <button type="button" class="d-none" id="searchUserDropdown" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">test</button>
    <div class="dropdown-menu w-100" aria-labelledby="searchUserDropdown" id="searchUserDropdownMenu">

    </div>
</div>
