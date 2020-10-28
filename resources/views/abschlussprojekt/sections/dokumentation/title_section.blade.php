{{-- Formularteil für Titel und Subtitel --}}

<div class="form-group row">
    <label class="control-label col-lg-2" for="{{ $v_name }}shortTitle">Kurzer Titel:</label>
    <input type="text" form="{{ $form }}" name="shortTitle" id="{{ $v_name }}shortTitle" @if($disable) disabled @else placeholder="kurzer Titel" @endif
           class="form-control col-lg-10 @error('shortTitle') is-invalid @enderror" value="{{ $documentation->getShortTitle($version) }}"/>
    @error('shortTitle') <p class="invalid-feedback">{{ $message }}</p> @enderror
</div>

<div class="form-group row">
    <label class="control-label col-lg-2" for="{{ $v_name }}longTitle">Langer Titel:</label>
    <input type="text" form="{{ $form }}" name="longTitle" id="{{ $v_name }}longTitle" @if($disable) disabled @else placeholder="langer Titel" @endif
           class="form-control col-lg-10 @error('longTitle') is-invalid @enderror" value="{{ $documentation->getLongTitle($version) }}"/>
    @error('longTitle') <p class="invalid-feedback">{{ $message }}</p> @enderror
</div>

@if(request()->is('*antrag') || request()->is('*antrag'))
    @include('abschlussprojekt.sections.kommentar',
        ['comments' => $version->getDocument()->comments->where('section_name', $s->name),])
@endif
