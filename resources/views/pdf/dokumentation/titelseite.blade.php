{{-- Titelseite eines Projektantrags --}}

<div id="titelseite">
    <p>
        <b>Abschlussprüfung TODO</b>
        <br/>
        Fachinformatiker/in, {{ $fachrichtung ?? 'PLACEHOLDER' }};
        <br/>
        Dokumentation zur betrieblichen Projektarbeit
        <br/>
    </p>
    <h2 class="heading">{{ $documentation->shortTitle }}</h2>
    <h3 class="heading">{{ $documentation->longTitle }}</h3>
    <p>
        <br/>
        Abgabedatum: Winnenden, den {{ $project->end }}
        <br/>
        <b>Prüfungsbewerber</b>
        <br/>
        {{ $project->user->full_name }}
        <br/>
        TODO: address
        <br/><br/>
        <b>Ausbildungsbetrieb:</b>
        <br/>
        Paulinenpflege Winnenden e.V.
        <br/>
        Linsenhalde 4
        <br/>
        71364 Winnenden
    </p>
</div>
