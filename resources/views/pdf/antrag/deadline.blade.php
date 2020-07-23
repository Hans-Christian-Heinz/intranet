{{-- Der Inhalt des Abschnitts Termin --}}

<p class="abschnitt">
    Beginn: {{ \Carbon\Carbon::create($proposal->getStart($version))->format('d.m.Y') }}<br/>
    Ende: {{ \Carbon\Carbon::create($proposal->getEnd($version))->format('d.m.Y') }}
</p>
