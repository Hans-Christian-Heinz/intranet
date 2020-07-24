{{-- Tabinhalt --}}
<div class="tab-content">
    @foreach($proposal->getSections($version) as $section)
        <div class="tab-pane @if($loop->first) active show @endif mt-2" id="{{ $v_name . $section->name }}"
             role="tabpanel" aria-labelledby="{{ $v_name . $section->name }}_tab">
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => 'formAntrag', 's' => $section,])
        </div>
    @endforeach
</div>
