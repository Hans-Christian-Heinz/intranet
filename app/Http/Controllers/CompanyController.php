<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get search query terms
        $q = request()->input("q", "");

        // Get all companies filtered by search query from database and paginate in 10. steps
        //$companies = Company::where("name", "LIKE", "%{$q}%")->orderBy("created_at", "DESC")->paginate(request("perPage", 10));
        $companies = Company::where("name", "LIKE", "%{$q}%")->orderBy("name")->paginate(request("perPage", 10));

        // Return companies overview page/view
        return view("bewerbungen.companies.index", compact("companies", "q"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return Company create form page/view
        return view("bewerbungen.companies.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate user input
        $attributes = $request->validate([
            "name" => ["required", "max:255"],
            "address" => ["required", "max:255"],
            "zip" => ["required", "max:255"],
            "city" => ["required", "max:255"],
            "state" => ["required", "max:255"],
            "country" => ["required", "max:255"],
            "description" => ["required"],
        ]);

        // Persist company into database
        $company = Company::create($attributes);

        // Redirect to created company show route with flash message
        return redirect()->route("bewerbungen.companies.show", $company)->with("status", "Die Firma {$company->name} wurde erfolgreich angelegt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        // Get and paginate all reviews for provided company
        $reviews = $company->reviews()->orderBy("created_at", "DESC")->paginate(10);

        // Return companies show view
        return view("bewerbungen.companies.show", compact("company", "reviews"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        // Return company edit view
        return view("bewerbungen.companies.edit", compact("company"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // validate user input
        $attributes = $request->validate([
            "name" => ["required", "max:255"],
            "address" => ["required", "max:255"],
            "zip" => ["required", "max:255"],
            "city" => ["required", "max:255"],
            "state" => ["required", "max:255"],
            "country" => ["required", "max:255"],
            "description" => ["required"],
        ]);

        // Persist changes on company model
        $company->update($attributes);

        // Redirect back to company show route with flash message
        return redirect()->route("bewerbungen.companies.show", $company)->with("status", "Die Änderungen wurde erfolgreich gespeichert.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //TODO ggf Methode entfernen (admin-route?)

        // Prevent non admins from accessing this route
        if (!app()->user->isAdmin()) {
            return abort(403);
        }

        // Delete company from database
        $company->delete();

        // Redirect to companies overview page with flash message
        return redirect()->route("bewerbungen.companies.index")->with("status", "Die Firma {$company->name} wurde erfolgreich gelöscht.");
    }
}
