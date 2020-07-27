{{-- Das Textfeld für die Ressourcenplanung hat einen anderen Platzhalter. --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="Kostenstelle : Beschreibung : 200,50;&#10;Kostenstelle2 : beschreibung : 1000;"
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}" @if($disable) disabled @endif>{{ $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
