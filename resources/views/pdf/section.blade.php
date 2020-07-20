{{----}}

@switch($tiefe)
    @case(1)
        <h1 class="heading">{{ $section->heading }}</h1>
        @break
    @case(2)
        <h2 class="heading">{{ $section->heading }}</h2>
        @break
    @case(3)
        <h3 class="heading">{{ $section->heading }}</h3>
        @break
    @case(4)
        <h4 class="heading">{{ $section->heading }}</h4>
        @break
    @case(5)
        <h5 class="heading">{{ $section->heading }}</h5>
        @break
    @default
        <h6 class="heading">{{ $section->heading }}</h6>
        @break
@endswitch


@if($section->name == 'phases')
    @include('pdf.antrag.phases_table')
@elseif($section->name == 'doku_phasen')
    @include('pdf.dokumentation.phases_table')
@elseif($section->tpl == 'dokumentation.ressourcen_text_section')
    @include('pdf.dokumentation.kostenstellen_table')
@elseif($section->tpl == 'dokumentation.ressourcen_gesamt_section')
    @include('pdf.dokumentation.kostenstellen_gesamt_table')
@elseif($section->name == 'soll_ist_vgl')
    @include('pdf.dokumentation.soll_ist_vgl')
@elseif($version->sections()->where('sections.section_id', $section->id)->count() == 0)
    <p class="abschnitt">{!! nl2br(e($section->getContent())) !!}</p>
@else
    @foreach($version->sections()->where('sections.section_id', $section->id)->orderBy('sequence')->get() as $child)
        <tocentry content="{{ $child->heading }}" level="{{ $tiefe }}"/>
        @include('pdf.section', ['section' => $child, 'tiefe' => $tiefe + 1,])
    @endforeach
@endif
