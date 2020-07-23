{{-- Gebe den Text eines Abschnittes aus. Füge ggf. Bilder ein --}}

<p class="abschnitt">
    {{-- Ersetze die Platzhalter (##PLACEHOLDER##) entweder mit Bildern (falls vorhanden) oder mit Zeilenumbrüchen --}}
    @foreach(explode('##PLACEHOLDER##', $section_text) as $i => $text)
        {{-- nl2br: new line to break konvertiert \n zu <br/> --}}
        {!! nl2br(e($text)) !!}
        @isset($section->images[$i])
            <br/>
            <div style="page-break-inside: avoid">
                <img src="{{ asset('storage/' . $section->images[$i]->path) }}" height="{{ $section->images[$i]->height }}"
                     width="{{ $section->images[$i]->width }}" alt="Die Bilddatei konnte nicht gefunden werden."/>
                <br/>
                <span class="footnote">{{ $section->images[$i]->footnote }}</span>
            </div>
            <br/>
            @else
                <br/>
        @endisset
        {{-- Falls noch Bilder auszugeben sind --}}
        @if($loop->last)
            @for($j = $i + 1; $j < $section->images->count(); $j++)
                <br/>
                <div style="page-break-inside: avoid">
                    <img src="{{ asset('storage/' . $section->images[$j]->path) }}" height="{{ $section->images[$j]->height }}"
                         width="{{ $section->images[$j]->width }}" alt="Die Bilddatei konnte nicht gefunden werden."/>
                    <br/>
                    <span class="footnote">{{ $section->images[$j]->footnote }}</span>
                </div>
                <br/>
            @endfor
        @endif
    @endforeach
</p>
