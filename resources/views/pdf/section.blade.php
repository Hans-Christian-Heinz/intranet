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

@if($version->sections()->where('sections.section_id', $section->id)->count() == 0)
    <p class="abschnitt">{!! nl2br(e($section->getContent())) !!}</p>
@endif

@if($section->name == 'phases')
    @include('pdf.antrag.phases_table')
@endif

@unless($section->name == 'phases')
    @foreach($version->sections()->where('sections.section_id', $section->id)->orderBy('sequence')->get() as $child)
        <tocentry content="{{ $section->heading }}" level="{{ $tiefe }}"/>
        @include('pdf.section', ['section' => $child, 'tiefe' => $tiefe + 1,])
    @endforeach
@endunless
