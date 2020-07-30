{{-- Gebe den Text eines Abschnittes aus. Füge ggf. Bilder ein --}}

<p class="abschnitt">
    @foreach($section->formatText() as $help)
        @if($help instanceof App\Structs\Link)
            <a href="#{{ $help->ziel }}">{{ $help->text }}</a>
        @elseif($help instanceof App\Structs\Table)
            <div style="page-break-inside: avoid">
                <tocentry content="Tabelle {{ $table_nr->nextNumber() . ': ' . $help->footer }}" name="toc_tables"/>
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
                <span class="footnote">Tabelle {{ $table_nr->getNumber() . ': ' . $help->footer }}</span>
            </div>
        @elseif($help instanceof App\Structs\ListStruct)
            @if($help->type == 'unordered')
                <ul>
                    @foreach($help->content as $c)
                        <li>{{ $c }}</li>
                    @endforeach
                </ul>
            @else
                <ol type="{{ $help->type }}">
                    @foreach($help->content as $c)
                        <li>{{ $c }}</li>
                    @endforeach
                </ol>
            @endif
        @elseif($help instanceof App\Structs\ImagePlaceholder)
            <br/>
            <div style="page-break-inside: avoid">
                <tocentry content="Abb {{ $image_nr->nextNumber() . ': ' . $section->images[$help->number]->footnote }}" name="toc_img"/>
                <img src="{{ asset('storage/' . $section->images[$help->number]->path) }}" height="{{ $section->images[$help->number]->height }}"
                     width="{{ $section->images[$help->number]->width }}" alt="Die Bilddatei konnte nicht gefunden werden."/>
                <br/>
                <span class="footnote">Abb {{ $image_nr->getNumber() . ': ' . $section->images[$help->number]->footnote }}</span>
            </div>
            <br/>
        @else
            {!! nl2br(e($help)) !!}
        @endif
    @endforeach
</p>
