{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $v_name }}documentationTab" role="tablist">
    @foreach($documentation->getSections($version) as $section)
        @include('abschlussprojekt.sections.nav_item_buttons')
    @endforeach
    @if(request()->is('*dokumentation'))
        {{-- Schaltfäche, um der Dokumentation einen neuen Abschnitt hinzuzufügen --}}
        <li class="nav-item border border-dark text-center py-3">
            <a class="btn btn-secondary" data-toggle="modal" href="#addSection">Abschnitt hinzufügen</a>
        </li>
    @endif
</ul>

@if(request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.dokumentation.addSectionModal', ['section' => null,])
@endif
