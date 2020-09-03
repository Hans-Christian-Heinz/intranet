{{-- Navigationslinks mi Schaltflächen zum Bearbeiten der Abschnitte --}}

<li class="nav-item border border-dark">
    {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
    <a class="nav-link @if($loop->first) active @endif @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif @error($section->name) fehler @enderror"
       aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
       aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
        {{ $section->heading }}
    </a>

    @if(request()->is('*dokumentation'))
        {{-- Hinzufügen eines Unterabsachnitts --}}
        <a @if($disable) href="#" @else href="#addSection{{ $section->id }}" @endif data-toggle="modal"
           style="line-height: 0.7rem" class="btn btn-secondary"><b>+</b></a>
        {{-- Löschen des Abschnitts (Unicode: Wastebinn --}}
        <a @if($disable) href="#" @else href="#deleteSection{{ $section->id }}" @endif data-toggle="modal"
           style="line-height: 0.7rem" class="btn btn-secondary float-right">&#128465</a>
        {{-- Bearbeiten des Abschnitts (Unicode: Bleistift) --}}
        <a @if($disable) href="#" @else href="#editSection{{ $section->id }}" @endif data-toggle="modal"
           style="line-height: 0.7rem" class="btn btn-secondary float-right">&#x270E</a>
    @endif

    {{-- Schaltfläche zum Sperren eines Abschnitts --}}
    @if((request()->is('*dokumentation') || request()->is('*antrag')) && is_null($section->section_id))
        <a @if(app()->user->isAdmin()) href="#lockSection{{ $section->id }}" @else href="#" @endif data-toggle="modal"
           style="line-height: 0.7rem" class="btn btn-secondary float-right">{!! $section->is_locked ? '&#128274' : '&#128275' !!}</a>
    @endif
</li>

@if(request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.dokumentation.addSectionModal')
    @include('abschlussprojekt.sections.dokumentation.deleteSectionModal')
    @include('abschlussprojekt.sections.dokumentation.editSectionModal')
@endif
@if(request()->is('*dokumentation') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.lockSectionModal')
@endif
