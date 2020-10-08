<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $categories = Category::orderBy("position")->paginate(10);

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
        $attributes['position'] = DB::table('categories')->count();

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
        $max = DB::table('categories')->count() - 1;
        return view("admin.bewerbungen.categories.edit", compact("category", "max"));
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
            "position" => "required|int|min:0|max:" . (DB::table('categories')->count() - 1),
        ]);

        $oldPos = $category->position;
        $newPos = $request->position;
        $category->update($attributes);

        //adjust the position field of other categories.
        if ($newPos < $oldPos) {
            DB::table('categories')->where('position', '>=', $newPos)
                ->where('position', '<', $oldPos)
                ->where('id', '<>', $category->id)
                ->increment('position', 1);
        }
        if ($newPos > $oldPos) {
            DB::table('categories')->where('position', '<=', $newPos)
                ->where('position', '>', $oldPos)
                ->where('id', '<>', $category->id)
                ->decrement('position', 1);
        }

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
        //adjust the position field of following categories.
        DB::table('categories')->where('position', '>', $category->position)->decrement('position', 1);

        return redirect()->route("admin.bewerbungen.categories.index")->with("status", "Die Kategorie {$category->name} wurde erfolgreich gelöscht");
    }
}
