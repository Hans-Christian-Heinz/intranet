{{-- Titelseite eines Projektantrags --}}

<img src="{{ asset("/img/ihk_logo.png") }}">
<span style="font-size: 25px;">Region Stuttgart</span>

<div id="titelseite">
    <h1 class="heading">Projektantrag</h1>
    <br/>
    <p>
        <b>Ausbildungsberuf</b>
        <br/>
        Fachinformatiker/in, {{ $fachrichtung ?? 'PLACEHOLDER' }};
        <br/>
        <br/>
        Auszubildender: {{ $project->user->full_name }}
        <br/>
        E-Mail: {{ $project->user->email }}
        <br/>
        <br/>
        Ausbildungsbetrieb: Paulinenpflege Winnenden e.V.
        <br/>
        Projektbetreuer: {{ $project->supervisor ? $project->supervisor->full_name : 'PLACEHOLDER' }}
        <br/>
        E-Mail: {{ $project->supervisor ? $project->supervisor->email : 'PLACEHOLDER' }}
        <br/>
        <br/>
        <b>Thema der Projektabrbeit</b>
        <br/>
        <b>{{ $proposal->topic }}</b>
    </p>
</div>
