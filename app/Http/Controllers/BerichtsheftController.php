<?php

namespace App\Http\Controllers;

use App\Berichtsheft;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerichtsheftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("berichtshefte.index", [
            "berichtshefte" => auth()->user()->berichtshefte()->orderBy("week", "DESC")->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $latestBerichtsheft = auth()->user()->berichtshefte()->orderBy("week", "DESC")->first();

        return view("berichtshefte.create", [
            "nextBerichtsheftDate" => ($latestBerichtsheft) ? $latestBerichtsheft->week->addWeek() : Carbon::now(),
            "nextBerichtsheftGrade" => ($latestBerichtsheft) ? $latestBerichtsheft->grade : 1
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = request()->validate([
            "grade" => "required|numeric|between:1,3",
            "work_activities" => "nullable|string",
            "instructions" => "nullable|string",
            "school" => "nullable|string",
            "week" => "required|date"
        ]);

        $attributes["week"] = Carbon::create($attributes["week"])->timestamp;

        $berichtsheft = auth()->user()->berichtshefte()->create($attributes);

        return redirect()->route("berichtshefte.edit", $berichtsheft)->with("status", "Berichtsheft wurde erfolgreich hinzugefügt.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function show(Berichtsheft $berichtsheft)
    {
        $this->authorize("show", $berichtsheft);

        $name = "Berichtsheft ({$berichtsheft->week->format('Y-\WW')})";

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            "mode" => "utf-8",
            "format" => "A4",

            "margin_left" => 20,
            "margin_right" => 20,
            "margin_top" => 20,
            "margin_bottom" => 20,

            "fontDir" => array_merge($fontDirs, [base_path() . "/resources/fonts"]),
            "fontdata" => $fontData + [
                "opensans" => [
                    "R" => "OpenSans-Regular.ttf",
                    "B" => "OpenSans-Bold.ttf"
                ]
            ],
            "default_font_size" => 12,
            "default_font" => "opensans",

            "tempDir" => sys_get_temp_dir()
        ]);

        $mpdf->SetTitle($name);

        $mpdf->WriteHTML(view("pdf.berichtsheft", compact("berichtsheft"))->render());

        return $mpdf->Output($name . ".pdf", "I");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function edit(Berichtsheft $berichtsheft)
    {
        $this->authorize("edit", $berichtsheft);

        $previousWeek = auth()->user()->berichtshefte()->where("week", "<", $berichtsheft->week)->orderBy("week", "DESC")->first();
        $nextWeek = auth()->user()->berichtshefte()->where("week", ">", $berichtsheft->week)->orderBy("week", "ASC")->first();

        return view("berichtshefte.edit", compact("berichtsheft", "previousWeek", "nextWeek"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Berichtsheft $berichtsheft)
    {
        $this->authorize("update", $berichtsheft);

        $attributes = request()->validate([
            "grade" => "required|numeric|between:1,3",
            "work_activities" => "nullable|string",
            "instructions" => "nullable|string",
            "school" => "nullable|string",
            "week" => "required|date"
        ]);

        $attributes["week"] = Carbon::create($attributes["week"])->timestamp;

        $berichtsheft->update($attributes);

        return back()->with("status", "Berichtsheft wurde erfolgreich gespeichert.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Berichtsheft  $berichtsheft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berichtsheft $berichtsheft)
    {
        $this->authorize("destroy", $berichtsheft);

        $berichtsheft->delete();

        return redirect()->route("berichtshefte.index")->with("status", "Berichtsheft wurde erfolgreich gelöscht.");
    }
}
