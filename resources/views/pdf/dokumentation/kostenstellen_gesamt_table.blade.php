{{----}}

<table>
    <tr class="bgHeader">
        <th>Beschreibung</th>
        <th>Kosten</th>
    </tr>
    @foreach($kostenstellen_gesamt as $name => $prize)
        <tr class="@if($loop->index % 2 == 0) bg0 @else bg1 @endif">
            @if($loop->last)
                <th>{{ $name }}</th>
                <th>{{ number_format($prize, 2) }}€</th>
            @else
                <td>{{ $name }}</td>
                <td>{{ number_format($prize, 2) }}€</td>
            @endif
        </tr>
    @endforeach
</table>
