{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="{{ $s->heading }}"
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror
