{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $v_name }}documentationTab" role="tablist">
    @foreach($documentation->getSections($version) as $section)
        @if(request()->is('*dokumentation'))
            @include('abschlussprojekt.sections.dokumentation.nav_item_buttons')
        @else
            <li class="nav-item border border-dark">
                {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
                <a class="nav-link @if($loop->first) active @endif @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif"
                   aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
                   aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
                    {{ $section->heading }}
                </a>
            </li>
        @endif
    @endforeach
    @if(request()->is('*dokumentation'))
        {{-- Schaltfäche, um der Dokumentation einen neuen Abschnitt hinzuzufügen --}}
        <li class="nav-item border border-dark text-center py-3">
            <a class="btn btn-secondary" data-toggle="modal" href="#addSection">Abschnitt hinzufügen</a>
        </li>
    @endif
</ul>

@include('abschlussprojekt.sections.dokumentation.addSectionModal', ['section' => null,])
