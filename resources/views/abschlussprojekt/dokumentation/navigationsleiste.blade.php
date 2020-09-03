{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $v_name }}documentationTab" role="tablist">
    @foreach($documentation->getSections($version) as $section)
        @include('abschlussprojekt.sections.nav_item_buttons', ['disable' => $disable || $section->is_locked,])
    @endforeach
    @if(request()->is('*dokumentation'))
        {{-- Schaltfäche, um der Dokumentation einen neuen Abschnitt hinzuzufügen --}}
        <li class="nav-item border border-dark text-center py-3">
            <a class="btn btn-secondary" data-toggle="modal" @if($disable) href="#" @else href="#addSection" @endif>Abschnitt hinzufügen</a>
        </li>
    @endif
</ul>

@if(request()->is('*dokumentation') && ! $disable)
    @include('abschlussprojekt.sections.dokumentation.addSectionModal', ['section' => null,])
@endif
