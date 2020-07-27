{{-- Navigationslinks mi Schaltflächen zum Bearbeiten der Abschnitte --}}

<li class="nav-item border border-dark">
    {{-- der eigentliche Link, der den entsprechenden Abschnitt öffnet. --}}
    <a class="nav-link" aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
       aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
        {{ $section->heading }}
    </a>

    {{-- Hinzufügen eines Unterabsachnitts --}}
    <a @if($disable) href="#" @else href="#addSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary"><b>+</b></a>
    {{-- Löschen des Abschnitts (Unicode: Wastebinn --}}
    <a @if($disable) href="#" @else href="#deleteSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary float-right">&#128465</a>
    {{-- Bearbeiten des Abschnitts (Unicode: Bleistift) --}}
    <a @if($disable) href="#" @else href="#editSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary float-right">&#x270E</a>
</li>

@include('abschlussprojekt.sections.dokumentation.addSectionModal')
@include('abschlussprojekt.sections.dokumentation.deleteSectionModal')
@include('abschlussprojekt.sections.dokumentation.editSectionModal')
