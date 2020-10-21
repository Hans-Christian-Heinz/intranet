@extends('layouts.app')

@section('title', "Bewerbungen: Betriebe · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex mb-3">
                        <h3 class="my-auto">Bewertung für <strong>{{ $company->name }}</strong></h3>

                        <div class="ml-auto my-auto">
                            <a href="{{ route("bewerbungen.companies.show", $company) }}" class="btn btn-sm btn-outline-secondary">Zurück</a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            {{-- <review-form :categories="{{ $categories }}" route="{{ route("companies.reviews.store", $company) }}"></review-form> --}}
                            <form action="{{ route("bewerbungen.companies.reviews.update", ["company" => $company, "review" => $review]) }}" method="POST">
                                @csrf
                                @method("PATCH")

                                <div class="form-group mb-4">
                                    <label for="comment">Bewertung</label>
                                    <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Begründen Sie Ihre Bewertung">{{ old("comment") ?: $review->comment }}</textarea>
                                    @error("comment")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                @foreach ($categories as $category)
                                    @php
                                        $rating = $review->ratings->where("category_id", $category->id)->first();
                                    @endphp
                                    @if ($rating)
                                        <review-category-form
                                            :category="{{ $category }}"
                                            comment="{{ old("category-" . $category->id . "-comment") ?: $rating->comment }}"
                                            :key="{{ $category->id }}"
                                            stars="{{ old("category-" . $category->id . "-stars") ?: $rating->stars }}"
                                        ></review-category-form>
                                    @else
                                        <review-category-form
                                            :category="{{ $category }}"
                                            comment="{{ old("category-" . $category->id . "-comment") ?: "" }}"
                                            :key="{{ $category->id }}"
                                            stars="{{ old("category-" . $category->id . "-stars") ?: 0 }}"
                                        ></review-category-form>
                                    @endif

                                    @error("category-" . $category->id . "-stars")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                    @error("category-" . $category->id . "-comment")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror

                                    <hr>
                                @endforeach

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary">Bewertung abgeben</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
