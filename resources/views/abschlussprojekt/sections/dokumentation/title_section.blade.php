{{-- Formularteil für Titel und Subtitel --}}

<div class="form-group row">
    <label class="control-label col-lg-2" for=shortTitle>Kurzer Titel:</label>
    <input type="text" form="{{ $form }}" name="shortTitle" placeholder="kurzer Titel" id="shortTitle" @if($disable) disabled @endif
           class="form-control col-lg-10 @error('shortTitle') is-invalid @enderror" value="{{ $documentation->getShortTitle($version) }}"/>
    @error('shortTitle') <p class="invalid-feedback">{{ $message }}</p> @enderror
</div>

<div class="form-group row">
    <label class="control-label col-lg-2" for=longTitle>Langer Titel:</label>
    <input type="text" form="{{ $form }}" name="longTitle" placeholder="langer Titel" id="longTitle" @if($disable) disabled @endif
           class="form-control col-lg-10 @error('longTitle') is-invalid @enderror" value="{{ $documentation->getLongTitle($version) }}"/>
    @error('longTitle') <p class="invalid-feedback">{{ $message }}</p> @enderror
</div>
