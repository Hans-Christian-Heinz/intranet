{{-- Formularteil für den Soll-Ist-Vergleich --}}

@php($zeitplanung = $documentation->getZeitplanung($version))

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="{{ $s->heading }}" @if($disable) disabled @endif
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ $zeitplanung['text'] }}</textarea>

@include('abschlussprojekt.sections.image_disclaimer')
@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

<table class="table table-striped my-4">
    <tr>
        <th>Projektphase</th>
        <th>Geplant</th>
        <th>Tatsächlich</th>
        <th>Differenz</th>
    </tr>
    @foreach($documentation->getPhasesDifference() as $name => $phase)
        @if($name !== 'gesamt')
            <tr>
                <td>{{ $phase['heading'] }}</td>
                <td>{{ $phase['duration'] }} h</td>
                <td>
                    <input type="number" style="width: 4rem;" step="1" min="0" form="{{ $form }}" id="{{ $name }}_input" name="{{ $name }}"
                           @if($disable) disabled @endif
                           class="form-control @error($name) is-invalid @enderror" value="{{ intval($zeitplanung[$name]) }}"/>
                    @error($name) <p class="invalid-feedback">{{ $message }}</p> @enderror
                    <label for="{{ $name }}_input">h</label>
                </td>
                <td>{{ $phase['difference'] }}</td>
            </tr>
        @else
            <tr>
                <td><b>{{ $phase['heading'] }}</b></td>
                <td><b>{{ $phase['duration'] }}</b></td>
                <td><b>{{ $zeitplanung[$name] }}</b></td>
                <td><b>{{ $phase['difference'] }}</b></td>
            </tr>
        @endif
    @endforeach
</table>

