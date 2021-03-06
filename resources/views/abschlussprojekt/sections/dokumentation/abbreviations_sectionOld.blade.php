{{-- Das Textfeld für die Ressourcenplanung hat einen anderen Platzhalter. --}}

<textarea @if($disable) disabled @else placeholder="SQL => Structured Query Language;&#10;HTML => Hypertext Markup Language;" @endif
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}" id="{{ $s->name }}_text" name="{{ $s->name }}">{{ old($s->name) ?: $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

Um ein Abkürzungsverzeichnis zu erstellen, geben Sie einfach Abkürzungen und ihre Bedeutung im Format eines assoziativen Arrays
(vgl. PHP) an; einzelne Einträge werden durch ";" getrennt. (Format: abkürzung => bedeutung;)

@if(request()->is('*dokumentation') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
