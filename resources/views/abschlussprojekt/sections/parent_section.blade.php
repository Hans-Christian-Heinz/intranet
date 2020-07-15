{{-- Für einen Abschnitt, der Unterabschnitte hat. --}}

{{-- Navigationsleiste --}}
<ul class="nav nav-tabs" id="{{ $s->name }}Tab" role="tablist">
    @foreach($s->sections as $section)
        <li class="nav-item border border-dark">
            <a class="nav-link" aria-selected="false" role="tab" id="{{ $section->name }}_tab"
               data-toggle="tab" aria-controls="{{ $section->name }}" href="#{{ $section->name }}">
                {{ $section->heading }}
            </a>
        </li>
    @endforeach
</ul>
{{-- Tabinhalt --}}
<div class="tab-content" id="{{ $s->name }}TabContent">
    @foreach($s->sections as $section)
        <div class="tab-pane mt-2" id="{{ $section->name }}" role="tabpanel" aria-labelledby="{{ $section->name }}_tab">
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => $form, 's' => $section,])
        </div>
    @endforeach
</div>
