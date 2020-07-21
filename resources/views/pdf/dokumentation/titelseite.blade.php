{{-- Titelseite eines Projektantrags --}}

<div id="titelseite">
    <img src="{{ asset("/img/ihk_logo.png") }}">

    <p>
        <b>Abschlussprüfung TODO</b>
        <br/>
        Fachinformatiker/in, {{ $fachrichtung ?? 'PLACEHOLDER' }};
        <br/><br/>
        <span style="font-size: {{ $format['textgroesse'] + 2 }}pt">Dokumentation zur betrieblichen Projektarbeit</span>
        <br/><br/>
    </p>
    <h2 class="heading">{{ $documentation->shortTitle }}</h2>
    <h3 class="heading">{{ $documentation->longTitle }}</h3>
    <p>
        <br/>
        Abgabedatum: Winnenden, den {{ \Carbon\Carbon::create($project->end)->format('d.m.Y') }}
        <br/><br/><br/>
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

    <img src="{{ asset("/img/bbw_logo.png") }}">
</div>
