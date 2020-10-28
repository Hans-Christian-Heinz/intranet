{{-- Das Textfeld für die Ressourcenplanung hat einen anderen Platzhalter. --}}

<documentation-table class="mb-5" name="Kostenstelle" template="{{ json_encode(App\Section::TABLETPLS['kostenstellen']) }}" val="{{ old($s->name) ?: $s->text }}"
                     section_name="{{ $s->name }}" form="{{ $form }}" disable="{{ $disable ? 'true' : null }}"></documentation-table>

@if(request()->is('*dokumentation') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
