<?php

namespace App\Http\Controllers;

use App\Category;
use App\Company;
use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $categories = Category::orderBy('position')->get();

        return view("bewerbungen.companies.reviews.create", compact("company", "categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $validationRules = [];
        $validationMessages = [];

        foreach (Category::all() as $category) {
            $validationRules["category-{$category->id}-stars"] = "required|numeric|between:0.5,5";
            $validationRules["category-{$category->id}-comment"] = "nullable";

            $validationMessages["category-{$category->id}-stars.between"] = "Bitte geben Sie die gewünschte Anzahl Sterne an.";
        }

        $attributes = $request->validate($validationRules, $validationMessages);

        $review = $company->reviews()->create([
            "user_id" => app()->user->id,
            "comment" => $request->input("comment")
        ]);

        foreach (Category::all() as $category) {
            $stars = 0;
            $comment = null;

            if ($request->input("category-{$category->id}-stars")) {
                $stars = $request->input("category-{$category->id}-stars");
            }

            if ($request->input("category-{$category->id}-comment")) {
                $comment = $request->input("category-{$category->id}-comment");
            }

            $review->ratings()->create([
                "category_id" => $category->id,
                "comment" => $comment,
                "stars" => $stars
            ]);
        }

        return redirect()->route("bewerbungen.companies.show", $company)->with("status", "Ihre Bewertung wurde erfolgreich abgegeben.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Review $review)
    {
        $categories = Category::all();

        return view("bewerbungen.companies.reviews.edit", compact("company", "review", "categories"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company, Review $review)
    {
        if ($review->user->id !== app()->user->id && !app()->user->isAdmin()) {
            return abort(403);
        }

        $validationRules = [];
        $validationMessages = [];

        foreach (Category::all() as $category) {
            $validationRules["category-{$category->id}-stars"] = "required|numeric|between:0.5,5";
            $validationRules["category-{$category->id}-comment"] = "nullable";

            $validationMessages["category-{$category->id}-stars.between"] = "Bitte geben Sie die gewünschte Anzahl Sterne an.";
        }

        $attributes = $request->validate($validationRules, $validationMessages);

        $review ->update([
            "comment" => $request->input("comment")
        ]);

        foreach (Category::all() as $category) {
            $rating = $review->ratings->where("category_id", $category->id)->first();

            $stars = 0;
            $comment = null;

            if ($request->input("category-{$category->id}-stars")) {
                $stars = $request->input("category-{$category->id}-stars");
            }

            if ($request->input("category-{$category->id}-comment")) {
                $comment = $request->input("category-{$category->id}-comment");
            }

            if (!$rating) {
                $review->ratings()->create([
                    "category_id" => $category->id,
                    "comment" => $comment,
                    "stars" => $stars
                ]);
            } else {
                $rating->update([
                    "comment" => $comment,
                    "stars" => $stars
                ]);
            }
        }

        return redirect()->route("bewerbungen.companies.show", $company)->with("status", "Ihre Bewertung wurde erfolgreich abgegeben.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Review $review)
    {
        if ($review->user->id !== app()->user->id && !app()->user->isAdmin()) {
            return abort(403);
        }

        $review->delete();

        return back()->with("status", "Ihre Bewertung wurde erfolgreich gelöscht.");
    }
}
