
<div style="page-break-after: right; page-break-before: right;">
<tocentry content="Eidesstattliche Erklärung" level="0"/>
<h1 class="heading">Eidesstattliche Erklärung</h1>
<p class="abschnitt">
    Ich, {{ $project->user->full_name }}, versichere hiermit, dass ich meine Dokumentation zur betrieblichen Projektarbeit mit
    dem Thema <br/>
    <i>{{ $documentation->shortTitle }} - {{ $documentation->longTitle }}</i> <br/>
    selbstständig verfasst und keine anderen als die angegebenden Quellen und Hilfsmittel benutzt habe, wobei ich alle
    wörtlichen und sinngemäßen Zitate als solche gekennzeichnet habe. Die Arbeit wurde bisher keiner anderen Prüfungsbehörde
    vorgelegt und auch nicht veröffentlicht. <br/><br/>
    Winnenden, den {{ \Carbon\Carbon::today()->format('d.m.Y') }} <br/><br/>
    ___________________________________________________________________________ <br/>
    {{ strtoupper($project->user->full_name) }}
</p>
</div>
