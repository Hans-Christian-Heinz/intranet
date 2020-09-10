{{----}}

{{-- An diese Stellen kann im Dokument gelinkt werden --}}
{{-- mit <a href="#{{ $section->name }}"></a> --}}
<a name="{{ $section->name }}" style="display: none"></a>
@switch($tiefe)
    @case(1)
        <h1 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h1>
        @break
    @case(2)
        <h2 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h2>
        @break
    @case(3)
        <h3 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h3>
        @break
    @case(4)
        <h4 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h4>
        @break
    @case(5)
        <h5 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h5>
        @break
    @default
        <h6 class="heading">{{ $section->getNumberedHeading($inhalt_counter, $version) }}</h6>
        @break
@endswitch


@if($section->name == 'phases')
    @include('pdf.antrag.phases_table')
@elseif($section->name == 'deadline')
    @include('pdf.antrag.deadline')
@elseif($section->name == 'doku_phasen')
    @include('pdf.dokumentation.phases_table')
@elseif($section->tpl == 'dokumentation.ressourcen_text_section')
    @include('pdf.dokumentation.kostenstellen_table')
@elseif($section->tpl == 'dokumentation.ressourcen_gesamt_section')
    @include('pdf.dokumentation.kostenstellen_gesamt_table')
@elseif($section->name == 'soll_ist_vgl')
    @include('pdf.dokumentation.soll_ist_vgl')
@elseif($section->name == 'abbreviations')
    @include('pdf.dokumentation.abbreviations')
@elseif($version->sections()->where('sections.section_id', $section->id)->count() == 0)
    @include('pdf.section_text')
@else
    @foreach($version->sections()->where('sections.section_id', $section->id)->orderBy('sequence')->get() as $child)
        <tocentry content="{{ $child->getNumberedHeading($inhalt_counter, $version) }}" level="{{ $tiefe }}"/>
        @include('pdf.section', ['section' => $child, 'tiefe' => $tiefe + 1,])
    @endforeach
@endif
