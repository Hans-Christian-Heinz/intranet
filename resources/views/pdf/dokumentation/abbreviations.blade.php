{{-- Abkürzungsverzeichnis --}}

<table style="border: 0">
    @foreach($documentation->abbreviations as $abbr => $val)
        <tr style="border: 0">
            <td style="border: 0">{{ $abbr }}</td>
            <td style="border: 0">{{ $val }}</td>
        </tr>
    @endforeach
</table>
