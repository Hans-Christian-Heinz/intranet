<div>
    <h6><strong>{{ $category }}</strong></h6>
    <p class="text-secondary">
        <star-rating-preview :stars="{{ $stars }}"></star-rating-preview> {{ $message ? "· " . $message : ""}}
    </p>
</div>
