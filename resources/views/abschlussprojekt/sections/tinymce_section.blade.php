{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird; verwendet tinymce um einen Editor zuur Verfügung zu stellen --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" @if($disable) disabled @else placeholder="{{ $s->heading }}" @endif
class="@unless($disable) section-content @endunless form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ old($s->name) ?: $s->text }}</textarea>

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror

@include('abschlussprojekt.sections.dokumentation.bilder.bilder')

@if(request()->is('*antrag') || request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
