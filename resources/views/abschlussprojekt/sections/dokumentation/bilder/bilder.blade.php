{{-- Zeige die Bilder eines Abschnitts an und stelle Möglichkeiten zur Bearbeitung zur Verfügung --}}
<div class="my-4">
    <h5 class="text-center">Bilder des Abschnitts</h5>
    <table class="table table-striped">
        @foreach($s->images as $image)
            <tr class="align-middle">
                <td>
                    <figure>
                        <img height="200" width="400" src="{{ asset('storage/' . $image->path) }}"
                             alt="Bilddatei wurde nicht gefunden"/>
                        <figcaption>{{ $image->footnote }}</figcaption>
                    </figure>
                </td>
                <td class="align-middle">
                    @if(request()->is('*dokumentation'))
                        <a data-toggle="modal" class="btn btn-secondary" @if($s->is_locked || $disable) href="#" @else href="#bildBearbeiten{{ $image->id }}" @endif>Bild bearbeiten</a>
                    @endif
                </td>
                <td class="align-middle">
                    @if(request()->is('*dokumentation'))
                        <a data-toggle="modal" class="btn btn-outline-danger" @if($s->is_locked || $disable) href="#" @else href="#bildEntfernen{{ $image->id }}" @endif>Bild entfernen</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>

@foreach($s->images as $image)
    @if(request()->is('*dokumentation') && ! $s->is_locked && ! $disable)
        @include('abschlussprojekt.sections.dokumentation.bilder.bildBearbeitenModal')
        @include('abschlussprojekt.sections.dokumentation.bilder.bildEntfernenModal')
    @endif
@endforeach
