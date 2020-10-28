{{-- Die Schaltflächen, die verwendet werden, um Abschnitte zu verwalten --}}

@if(request()->is('*dokumentation'))
    {{-- Hinzufügen eines Unterabsachnitts --}}
    <a @if($disable) href="#" @else href="#addSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary">
        <b data-toggle="tooltip" data-placement="top" title="Unterabschnitt hinzufügen">+</b>
    </a>
    {{-- Löschen des Abschnitts (Unicode: Wastebinn --}}
    <a @if($disable) href="#" @else href="#deleteSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary float-right">
        <span data-toggle="tooltip" data-placement="top" title="Abschnitt löschen">&#128465</span>
    </a>
    {{-- Bearbeiten des Abschnitts (Unicode: Bleistift) --}}
    <a @if($disable) href="#" @else href="#editSection{{ $section->id }}" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary float-right">
        <span data-toggle="tooltip" data-placement="top" title="Abschnitt bearbeiten">&#x270E</span>
    </a>
@endif

{{-- Schaltfläche zum Sperren eines Abschnitts --}}
@if((request()->is('*dokumentation') || request()->is('*antrag')) && is_null($section->section_id))
    <a @if(app()->user->isAdmin()) href="#lockSection{{ $section->id }}" @else href="#" @endif data-toggle="modal"
       style="line-height: 0.7rem" class="btn btn-secondary float-right">
        {!! $section->is_locked
            ? '<span data-toggle="tooltip" data-placement="top" title="Abschnitt freigeben">&#128274</span>'
            : '<span data-toggle="tooltip" data-placement="top" title="Abschnitt sperren">&#128275</span>'
        !!}
    </a>
@endif
