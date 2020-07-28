{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $v_name }}proposalTab" role="tablist">
    @foreach($proposal->getSections($version) as $section)
        @include('abschlussprojekt.sections.nav_item_buttons')
    @endforeach
</ul>
