<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                <strong>{{ $application->company->name }}</strong><br>
                {{ $application->company->address }}<br>
                {{ $application->company->zip . " " . $application->company->city }}<br>
            </td>
            <td style="text-align: right;">
                {{ auth()->user()->name }}<br>
                {{ $resume->personal->address }}<br>
                {{ $resume->personal->zip . " " . $resume->personal->city }}<br>
                {{ $resume->personal->phone }}<br>
                {{ auth()->user()->email }}
            </td>
        </tr>
    </tbody>
</table>

<p style="margin-top: 50px;">{{ $content->greeting->body }}</p>

<p>{{ $content->awareofyou->body }}</p>

<p>{{ $content->currentactivity->body }}</p>

<p>{{ $content->whycontact->body }}</p>

@php
    $wayOfWork = $content->wayOfWork;
    $lastWayOfWork = empty($wayOfWork) ? "" :$content->wayOfWork[count($content->wayOfWork) - 1];
    $skills = $content->skills;
    $lastSkill = empty($skills) ? "" : $content->skills[count($content->skills) - 1];

    if (count($wayOfWork) > 1) {
        array_pop($wayOfWork);
    }

    if (count($skills) > 1) {
        array_pop($skills);
    }

    $wayOfWorkMessage = (count($content->wayOfWork) > 1) ? join(", ", $wayOfWork) . " und " . $lastWayOfWork : $lastWayOfWork;
    $skillsMessage = (count($content->skills) > 1) ? join(", ", $skills) . " und " . $lastSkill : $lastSkill;

    $finalMessage = "In eine neue Aufgabe bei Ihnen kann ich verschiedene Stärken einbringen. So bin ich meine Aufgaben sehr {$wayOfWorkMessage} angegangen. Mit mir gewinnt Ihr Unternehmen einen Mitarbeiter, der {$skillsMessage} ist. Außerdem habe ich in früheren Projekten insbesondere ausgeprägte Kommunikationsstärke, hohe Lernbereitschaft und viel Kreativität unter Beweis stellen können.";
@endphp

<p>{{ $finalMessage }}</p>

<p>{{ $content->ending->body }}</p>

<p>Mit freundlichen Grüßen</p>

<p style="margin-top: 60px;">{{ app()->user->full_name }}, {{ Carbon\Carbon::now()->format("d.m.Y") }}</p>
