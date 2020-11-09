{{-- Generiere aus dem Inhalt eines Abschnitts (SectionTextComponent.vue) die passende Ausgabe --}}

<p class="abschnitt">
    @foreach (json_decode($section->text, true) as $help)
        @switch($help['type'])
            @case('text')
                {!! nl2br(e($help['val'])) !!}
                @break
            @case('table')
                <div style="page-break-inside: avoid">
                    <tocentry content="Tabelle {{ $table_nr->nextNumber() . ': ' . $help['caption'] }}" name="toc_tables"/>
                    <table>
                        <caption>Tabelle {{ $table_nr->getNumber() . ': ' . $help['caption'] }}</caption>
                        @foreach($help['rows'] as $row)
                            <tr class="@if($row['is_header']) bgHeader @elseif($loop->index % 2 == 0) bg0 @else bg1 @endif">
                                @foreach($row['cols'] as $c)
                                    @if($row['is_header'])
                                        <th>{{ $c['text'] }}</th>
                                    @else
                                        <td>{{ $c['text'] }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
                @break
            @case('list')
                @if($help['order'] == 'unordered')
                    <ul>
                        @foreach($help['items'] as $item)
                            <li>{{ $item['text'] }}</li>
                        @endforeach
                    </ul>
                @else
                    <ol type="{{ $help['order'] }}">
                        @foreach($help['items'] as $item)
                            <li>{{ $item['text'] }}</li>
                        @endforeach
                    </ol>
                @endif
                @break
            @case('link')
                <a href="#{{ $help['target'] }}">{{ $help['text'] }}</a>
                @break
            @case('img')
            <br/>
            <div style="page-break-inside: avoid">
                <tocentry content="Abb {{ $image_nr->nextNumber() . ': ' . $help['footnote'] }}" name="toc_img"/>
                <figure>
                    {{--<img src="{{ asset('storage/' . $section->images[$help->number]->path) }}" height="{{ $section->images[$help->number]->height }}mm"
                         width="{{ $section->images[$help->number]->width }}mm" alt="Die Bilddatei konnte nicht gefunden werden."/>--}}
                    <img src="{{ storage_path('app/public/' . $help['path']) }}" height="{{ $help['height'] }}mm"
                         width="{{ $help['width'] }}mm" alt="Die Bilddatei konnte nicht gefunden werden."/>
                    <figcaption>Abb {{ $image_nr->getNumber() . ': ' . $help['footnote'] }}</figcaption>
                </figure>
            </div>
            <br/>
        @endswitch
    @endforeach
</p>
