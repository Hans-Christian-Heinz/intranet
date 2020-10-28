{{-- Navigationsleiste, ggf verschachtelt --}}

<ul class="nav nav-tabs flex-column" @if(! $s instanceof App\Section)style="max-height: 40rem; overflow-y: scroll; flex-wrap: nowrap;" @endif
    id="{{ $v_name . $name }}Tab" role="tablist">
    @foreach($s->getSections($version) as $section)
        <li class="nav-item border">
            @if($section->getSections($version)->count() > 0)
                <a class="nav-link @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif @error($section->name) fehler @enderror"
                   data-toggle="collapse" role="button" aria-expanded="false" href="#{{ $v_name . $section->name }}Collapse" aria-controls="{{ $v_name . $section->name }}Collapse">
                    {{ $section->heading }} <span class="caret"></span>
                </a>
                @include('abschlussprojekt.sections.navigationButtonsNew')
                {{-- navigation-collapse wird im Skript benutzerfreundlichkeit.js verwendet --}}
                <div class="navigation-collapse collapse" style="width: 90%; float: right;" id="{{ $v_name . $section->name }}Collapse">
                    @include('abschlussprojekt.sections.sectionNavigation', ['s' => $section, 'name' => $section->name])
                </div>
            @else
                {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
                <a class="nav-link navigationlink @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif @error($section->name) fehler @enderror"
                   aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
                   aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
                    {{ $section->heading }}
                </a>
                @include('abschlussprojekt.sections.navigationButtonsNew')
            @endif
        </li>
    @endforeach
    @if(request()->is('*dokumentation') && $s instanceof App\Documentation)
        {{-- Schaltfäche, um der Dokumentation einen neuen Abschnitt hinzuzufügen --}}
        <li class="nav-item border text-center py-3">
            <a class="btn btn-secondary" data-toggle="modal" @if($disable) href="#" @else href="#addSection" @endif>Abschnitt hinzufügen</a>
        </li>
    @endif
</ul>

@if(request()->is('*dokumentation') && ! $disable)
    @include('abschlussprojekt.sections.dokumentation.addSectionModal', ['section' => null,])
@endif

@foreach($s->getSections($version) as $section)
    @if(request()->is('*dokumentation') && !$disable)
        @include('abschlussprojekt.sections.dokumentation.addSectionModal')
        @include('abschlussprojekt.sections.dokumentation.deleteSectionModal')
        @include('abschlussprojekt.sections.dokumentation.editSectionModal')
    @endif
    @if((request()->is('*dokumentation') || request()->is('*antrag')) && app()->user->isAdmin())
        @include('abschlussprojekt.sections.lockSectionModal')
    @endif
@endforeach
