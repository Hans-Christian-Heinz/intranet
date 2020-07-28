{{-- Für einen Abschnitt, der Unterabschnitte hat. --}}

{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $s->name }}Tab" role="tablist">
    @foreach($s->getSections($version) as $section)
        @include('abschlussprojekt.sections.nav_item_buttons')
    @endforeach
</ul>
{{-- Tabinhalt --}}
<div class="tab-content" id="{{ $s->name }}TabContent">
    @foreach($s->getSections($version) as $section)
        <div class="tab-pane @if($loop->first) active show @endif mt-2" id="{{ $v_name . $section->name }}"
             role="tabpanel" aria-labelledby="{{ $v_name . $section->name }}_tab">
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => $form, 's' => $section, 'disable' => $disable || $section->is_locked,])
        </div>
    @endforeach
</div>
