{{-- Gebe den Text eines Abschnittes aus. Füge ggf. Bilder ein --}}

<p class="abschnitt">
    @foreach($section->formatText() as $help)
        @if($help instanceof App\Structs\Link)
            <a href="#{{ $help->ziel }}">{{ $help->text }}</a>
        @elseif($help instanceof App\Structs\Table)
            <table>
                @foreach($help->rows as $row)
                    <tr class="@if($row->isHeader) bgHeader @elseif($loop->index % 2 == 0) bg0 @else bg1 @endif">
                        @foreach($row->content as $c)
                            @if($row->isHeader)
                                <th>{{ $c }}</th>
                            @else
                                <td>{{ $c }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </table>
        @elseif($help instanceof App\Structs\ListStruct)
            @switch($help->type)
                @case('letters')
                    <ol type="a">
                        @foreach($help->content as $c)
                            <li>{{ $c }}</li>
                        @endforeach
                    </ol>
                    @break
                @case('numbers')
                    <ol type="1">
                        @foreach($help->content as $c)
                            <li>{{ $c }}</li>
                        @endforeach
                    </ol>
                    @break
                @default
                    <ul>
                        @foreach($help->content as $c)
                            <li>{{ $c }}</li>
                        @endforeach
                    </ul>
                    @break
            @endswitch
        @elseif($help instanceof App\Structs\ImagePlaceholder)
            <br/>
            <div style="page-break-inside: avoid">
                <img src="{{ asset('storage/' . $section->images[$help->number]->path) }}" height="{{ $section->images[$help->number]->height }}"
                     width="{{ $section->images[$help->number]->width }}" alt="Die Bilddatei konnte nicht gefunden werden."/>
                <br/>
                <span class="footnote">{{ $section->images[$help->number]->footnote }}</span>
            </div>
            <br/>
        @else
            {!! nl2br(e($help)) !!}
        @endif
    @endforeach
</p>
