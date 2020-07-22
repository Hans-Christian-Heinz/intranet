{{-- Ein Abschnitt, der durch ein Textfeld beschrieben wird --}}

<textarea id="{{ $s->name }}_text" name="{{ $s->name }}" placeholder="{{ $s->heading }}" @if($disable) disabled @endif
          class="form-control mt-2 @error($s->name) is-invalid @enderror" form="{{ $form }}">{{ $s->text }}</textarea>

@include('abschlussprojekt.sections.image_disclaimer')

@foreach($s->images as $image)
    <img height="{{ $image->height }}" width="{{ $image->width }}" src="{{ asset('storage/' . $image->path) }}"
         alt="Bilddatei wurde nicht gefunden"/>
@endforeach

@error($s->name) <p class="invalid-feedback">{{ $message }}</p> @enderror
