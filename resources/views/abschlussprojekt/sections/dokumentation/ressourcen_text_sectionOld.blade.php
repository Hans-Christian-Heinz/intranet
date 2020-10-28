{{-- Das Textfeld für die Ressourcenplanung hat einen anderen Platzhalter. --}}

<textarea @if($disable) disabled @else placeholder="Kostenstelle : Beschreibung : 200,50;&#10;Kostenstelle2 : beschreibung : 1000;" @endif
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}" id="{{ $s->name }}_text" name="{{ $s->name }}">{{ old($s->name) ?: $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

<p class="my-3">Einzelne Kostenstellen werden durch ";" abgegrenzt. Eine Kostenstelle hat das Format "Name : Beschreibung : Kosten in €". Die Kosten dürfen maximal zwei Nachkommastellen haben.</p>

@if(request()->is('*dokumentation') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
