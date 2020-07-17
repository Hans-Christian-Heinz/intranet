{{-- Für einen Abschnitt, der Unterabschnitte hat. --}}

{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $s->name }}Tab" role="tablist">
    @foreach($s->getSections($version) as $section)
        <li class="nav-item border border-dark">
            {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
            <a class="nav-link @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif"
               aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
               aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
                {{ $section->heading }}
            </a>
        </li>
    @endforeach
</ul>
{{-- Tabinhalt --}}
<div class="tab-content" id="{{ $s->name }}TabContent">
    @foreach($s->getSections($version) as $section)
        <div class="tab-pane mt-2" id="{{ $v_name . $section->name }}" role="tabpanel" aria-labelledby="{{ $v_name . $section->name }}_tab">
            @include('abschlussprojekt.sections.' . $section->tpl, ['form' => $form, 's' => $section,])
        </div>
    @endforeach
</div>
