{{-- Das Textfeld für die Phasen hat einen anderen Platzhalter. --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="BspPhase : 2;&#10;BspPhase2 : 1;"
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror
