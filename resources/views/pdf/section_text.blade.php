{{-- Gebe den Text eines Abschnittes aus. Füge ggf. Bilder ein --}}

<p class="abschnitt">
    @foreach($section->formatText($table_nr) as $help)
        @if($help instanceof App\Structs\Link)
            <a href="#{{ $help->ziel }}">{{ $help->text }}</a>
        {{--@elseif($help instanceof App\Structs\Table)
            <div style="page-break-inside: avoid">
                <tocentry content="Tabelle {{ $table_nr->nextNumber() . ': ' . $help->footer }}" name="toc_tables"/>
                <table>
                    <caption>Tabelle {{ $table_nr->getNumber() . ': ' . $help->footer }}</caption>
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
            @endif--}}
        @elseif($help instanceof App\Structs\ImagePlaceholder)
            <br/>
            <div style="page-break-inside: avoid">
                <tocentry content="Abb {{ $image_nr->nextNumber() . ': ' . $section->images[$help->number]->footnote }}" name="toc_img"/>
                <figure>
                    <img src="{{ asset('storage/' . $section->images[$help->number]->path) }}" height="{{ $section->images[$help->number]->height }}mm"
                         width="{{ $section->images[$help->number]->width }}mm" alt="Die Bilddatei konnte nicht gefunden werden."/>
                    <figcaption>Abb {{ $image_nr->getNumber() . ': ' . $section->images[$help->number]->footnote }}</figcaption>
                </figure>
            </div>
            <br/>
        @else
            {{--@if($section->tpl == 'tinymce_section')
                {!! $help !!}
            @else
                {!! nl2br(e($help)) !!}
            @endif--}}
            {!! $help !!}
        @endif
    @endforeach
</p>
