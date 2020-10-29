{{-- Tabinhalt --}}

@foreach($s->getSections($version) as $section)
    @if($section->getSections($version)->count() > 0)
        @include('abschlussprojekt.sections.tabinhaltNeu', ['s' => $section])
    @else
        <div class="tab-pane mt-2" id="{{ $v_name . $section->name }}"
             role="tabpanel" aria-labelledby="{{ $v_name . $section->name }}_tab">
            <h5 class="text-center">{{ $section->heading }}</h5>
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => $form, 's' => $section, 'disable' => $disable || $section->is_locked,])
        </div>
    @endif
@endforeach
