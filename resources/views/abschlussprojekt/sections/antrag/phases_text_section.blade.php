{{-- Das Textfeld für die Phasen hat einen anderen Platzhalter. --}}

<documentation-table class="mb-5" name="Phase" template="{{ json_encode(App\Section::TABLETPLS['phases']) }}" val="{{ old($s->name) ?:$s->text }}"
                     section_name="{{ $s->name }}" form="{{ $form }}" disable="{{ $disable ? 'true' : null }}"></documentation-table>

@error($s->name) <p class="text-danger">{{ $message }}</p> @enderror

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
