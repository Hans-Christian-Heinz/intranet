{{-- Das Textfeld für die Phasen hat einen anderen Platzhalter. --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" @if($disable) disabled @else placeholder="BspPhase : 2;&#10;BspPhase2 : 1;" @endif
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

<p class="my-2">Einzelne Phasen werden durch ";" abgegrenzt. Eine Phase hat das Format "Phasenname : Dauer in h". Die Dauer muss eine natürliche Zahl sein.</p>

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
