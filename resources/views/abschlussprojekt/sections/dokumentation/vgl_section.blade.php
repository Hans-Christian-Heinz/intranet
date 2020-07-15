{{-- Formularteil für den Soll-Ist-Vergleich --}}

@include('abschlussprojekt.sections.text_section')

<table class="table table-striped my-4">
    <tr>
        <th>Projektphase</th>
        <th>Geplante</th>
        <th>Tatsächlich</th>
        <th>Differenz</th>
    </tr>
    @foreach($documentation->getPhasesDifference(true) as $name => $phase)
        @if($name !== 'gesamt')
            <tr>
                <td>{{ $phase['heading'] }}</td>
                <td>{{ $phase['duration'] }} h</td>
                <td>
                    <input type="number" style="width: 4rem;" step="1" min="0" form="{{ $form }}" id="{{ $name }}_input" name="{{ $name }}"
                           class="form-control @error($name) is-invalid @enderror" value="{{ $documentation->$name }}"/>
                    @error($name) <p class="invalid-feedback">{{ $message }}</p> @enderror
                    <label for="{{ $name }}_input">h</label>
                </td>
                <td>{{ $phase['difference'] }}</td>
            </tr>
        @else
            <tr>
                <td><b>{{ $phase['heading'] }}</b></td>
                <td><b>{{ $phase['duration'] }}</b></td>
                <td><b>{{ $documentation->$name }}</b></td>
                <td><b>{{ $phase['difference'] }}</b></td>
            </tr>
        @endif
    @endforeach
</table>
