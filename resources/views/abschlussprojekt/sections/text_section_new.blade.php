{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird --}}

<section-text text="{{ old($s->name) ?:$s->text }}" available_images="{{ json_encode($availableImages) }}" available_sections="{{ json_encode($availableSections) }}"
              name="{{ $s->name }}" disable="{{ $disable }}" @error($s->name) class="is-invalid" @enderror form="{{ $form }}"></section-text>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

@if(request()->is('*antrag') || request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
