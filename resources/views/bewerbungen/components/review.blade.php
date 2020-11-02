<div class="card mb-3">
    <div class="card-body">
        <div class="rating-author d-flex justify-content-between mb-2">
            <div class="d-flex">
                {{-- Profile picture --}}
                <svg xmlns="http://www.w3.org/2000/svg" height="50" viewBox="0 0 24 24" width="50" style="opacity: .25;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/><path d="M0 0h24v24H0z" fill="none"/></svg>

                {{-- User and Review Info --}}
                <div class="rating-author-info ml-1 my-auto">
                    <h5 class="my-auto">{{ $review->user->full_name }}</h5>
                    <p class="text-secondary mb-0">
                        <star-rating-preview :stars="{{ $review->rating() }}"></star-rating-preview> · <small>{{ $review->created_at->format("d.m.Y") }}</small>
                    </p>
                </div>
            </div>

            @if (app()->user->id === $review->user->id || app()->user->isAdmin())
                <div>
                    <a href="{{ route("bewerbungen.companies.reviews.edit", ["company" => $review->company, "review" => $review]) }}" class="text-secondary"><small>Bearbeiten</small></a>
                    <a href="#" class="text-danger ml-2" data-toggle="modal" data-target="#deleteReviewModal{{ $review->id }}"><small>Löschen</small></a>
                </div>

                @push('modals')
                    <x-modal id="deleteReviewModal{{ $review->id }}" title="Bewertung löschen">
                        <x-slot name="body">
                            <p class="text-center mb-0 py-3">Möchten Sie Ihre Bewertung wirklich löschen?</p>
                        </x-slot>
                        <x-slot name="footer">
                            <form action="{{ route("bewerbungen.reviews.destroy", ["company" => $review->company, "review" => $review]) }}" method="post">
                                @csrf
                                @method("DELETE")

                                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-danger">Bewertung löschen</button>
                            </form>
                        </x-slot>
                    </x-modal>
                @endpush
            @endif
        </div>

        <h6 class="mt-3">Kommentar</h6>
        <p class="mb-0 text-secondary">
            {{ $review->comment }}
        </p>

        <hr>

        {{-- Ratings --}}
        @foreach ($review->orderedRatings as $rating)
            <x-review-category :category="$rating->category->name" :stars="$rating->stars" :message="$rating->comment" />
        @endforeach

    </div>
</div>
