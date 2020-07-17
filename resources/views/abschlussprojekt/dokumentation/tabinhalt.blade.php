
{{-- Tabinhalt --}}
<div class="tab-content">
    @foreach($documentation->getSections($version) as $section)
        <div class="tab-pane mt-2" id="{{ $v_name . $section->name }}" role="tabpanel" aria-labelledby="{{ $v_name . $section->name }}_tab">
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => 'formDokumentation', 's' => $section,])
        </div>
    @endforeach
</div>