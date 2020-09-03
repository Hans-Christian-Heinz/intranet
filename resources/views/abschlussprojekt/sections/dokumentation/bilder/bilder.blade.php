{{-- Zeige die Bilder eines Abschnitts an und stelle Möglichkeiten zur Bearbeitung zur Verfügung --}}

@if(request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.dokumentation.image_disclaimer')
@endif

<table class="table table-striped">
    @foreach($s->images as $image)
        <tr class="align-middle">
            <td>
                <img height="{{ $image->height }}" width="{{ $image->width }}" src="{{ asset('storage/' . $image->path) }}"
                     alt="Bilddatei wurde nicht gefunden"/>
                <br/>
                <span>{{ $image->footnote }}</span>
            </td>
            <td>
                @if(request()->is('*dokumentation'))
                    <a data-toggle="modal" class="btn btn-secondary" @if($s->is_locked || $disable) href="#" @else href="#bildBearbeiten{{ $image->id }}" @endif>Bild bearbeiten</a>
                @endif
            </td>
            <td>
                @if(request()->is('*dokumentation'))
                    <a data-toggle="modal" class="btn btn-outline-danger" @if($s->is_locked || $disable) href="#" @else href="#bildEntfernen{{ $image->id }}" @endif>Bild entfernen</a>
                @endif
            </td>
        </tr>
    @endforeach
</table>

@foreach($s->images as $image)
    @if(request()->is('*dokumentation') && ! $s->is_locked && ! $disable)
        @include('abschlussprojekt.sections.dokumentation.bilder.bildBearbeitenModal')
        @include('abschlussprojekt.sections.dokumentation.bilder.bildEntfernenModal')
    @endif
@endforeach
