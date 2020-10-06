<h1>{{ $resume->user->full_name }}</h1>

<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                {{-- Left Side --}}
                <h3>Persönliche Daten</h3>
                <hr>

                <p><strong>Name</strong></p>
                <p>{{ $resume->user->name }}</p>

                <p><strong>Anschrift</strong></p>
                <p>{{ $content->personal->address }} <br> {{ $content->personal->zip }} {{ $content->personal->city }}</p>

                <p><strong>Tel.</strong></p>
                <p>{{ $content->personal->phone }}</p>

                <p><strong>E-Mail</strong></p>
                <p>{{ $content->personal->email }}</p>

                <p><strong>geb.</strong></p>
                <p>{{ (new Carbon\Carbon($content->personal->birthday))->format("d.m.Y") }}</p>

                <br><br>
                <h3>Ausbildung</h3>
                <hr>
                @foreach ($content->education as $index => $education)
                    <p><strong>{{ $education->time }}</strong></p>
                    <p>{{ $education->description }}</p>
                    @if ($index !== count($content->education) - 1)
                        <br>
                    @endif
                @endforeach

                <br><br>
                <h3>Kenntnisse & Fähigkeiten</h3>
                <hr>
                @foreach ($content->skills as $index => $skill)
                    <p><strong>{{ $skill->title }}</strong></p>
                    <p>{{ $skill->description }}</p>
                    @if ($index !== count($content->skills) - 1)
                        <br>
                    @endif
                @endforeach
            </td>
            <td valign="top">
                {{-- Right Side --}}
                <h3>Berufliche Laufbahn</h3>
                <hr>
                @foreach ($content->careers as $index => $career)
                    <p><strong>{{ $career->time }}</strong></p>
                    <p><strong>{{ $career->company }}</strong></p>
                    <p>{{ $career->description }}</p>
                    @if ($index !== count($content->skills) - 1)
                        <br>
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
