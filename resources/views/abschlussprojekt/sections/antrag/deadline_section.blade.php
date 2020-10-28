<table class="table table-striped">
    <tbody>
    <tr>
        <td>
            <label for="start">Beginn:</label>
        </td>
        <td>
            <input type="date" id="start" class="form-control @error('start') is-invalid @enderror" @if($disable) disabled @endif
                   name="start" form="{{ $form }}" @isset($proposal->start) value="{{ old("start") ?:$proposal->getStart($version) }}" @endisset/>

            @error("start") <p class="invalid-feedback">{{ $message }}</p> @enderror
        </td>
    </tr>
    <tr>
        <td>
            <label for="end">Ende:</label>
        </td>
        <td>
            <input type="date" id="end" class="form-control @error('end') is-invalid @enderror" @if($disable) disabled @endif
                   name="end" form="{{ $form }}" @isset($proposal->end) value="{{ old("end") ?:$proposal->getEnd($version) }}" @endisset/>

            @error("end") <p class="invalid-feedback">{{ $message }}</p> @enderror
        </td>
    </tr>
    </tbody>
</table>

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
