{{-- Formularteil für die Summe der Kostenstellen; nicht ausfüllbar, Werte werden in den Abschnitten Hardware, Software und Personal bestimmt. --}}

<table class="table table-striped">
    <tr>
        <th>Beschreibung</th>
        <th>Kosten</th>
    </tr>
    @foreach($documentation->getKostenstellenGesamt($version) as $kategorie => $kosten)
        <tr>
            <td>{{ $kategorie }}</td>
            <td>{{ $kosten }} €</td>
        </tr>
    @endforeach
</table>

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
