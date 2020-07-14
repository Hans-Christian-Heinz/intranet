{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird --}}

<textarea id="{{ $section->name }}_text" name="{{ $section->name }}" placeholder="{{ $section->heading }}"
          class="form-control mt-2" form="{{ $form }}">{{ $section->text }}</textarea>
