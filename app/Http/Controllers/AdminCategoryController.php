<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all categories from the database
        $categories = Category::orderBy("created_at", "DESC")->paginate(10);

        return view("admin.bewerbungen.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.bewerbungen.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            "name" => "required",
        ]);

        $category = Category::create($attributes);

        return redirect()->route("admin.bewerbungen.categories.index")->with("status", "Die Kategorie {$category->name} wurde erfolgreich hinzugrfügt");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view("admin.bewerbungen.categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $attributes = $request->validate([
            "name" => "required",
        ]);

        $category->update($attributes);

        return redirect()->route("admin.bewerbungen.categories.edit", $category)->with("status", "Die Änderungen wurden erfolgreich gespeichert");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $reviewCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route("admin.bewerbungen.categories.index")->with("status", "Die Kategorie {$category->name} wurde erfolgreich gelöscht");
    }
}
