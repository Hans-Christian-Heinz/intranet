{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="{{ $s->heading }}"
          class="form-control mt-2" form="{{ $form }}">{{ $s->text }}</textarea>
