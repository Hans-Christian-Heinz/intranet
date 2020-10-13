<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicationTemplate extends Model
{
    protected $table = 'application_tpls';
    
    protected $with = ['keywords'];
    
    private static $at_table = 'application_tpls';
    private static $kw_table = 'keyword_tpls';
    
    /**
     * Hinterlege in der Datenbank die Standardwerte (mit der höchsten Versionsnummer)
     */
    public static function restoreDefault() {
        $table = self::$at_table;
        $kw_table = self::$kw_table;
        
        $version = DB::table($table)->max('version');
        is_null($version) ? $version = 0 : $version++;
        
        DB::table($table)->insert([
            ['name' => 'heading', 'heading' => 'Überschrift', 'version' => $version, 'fix' => true, 'is_heading' => true, 'choose_keywords' => false, 'tpls' => '["Bewerbung auf die Stelle als Musterstelle", "Bewerbung auf die ausgeschriebene Stelle 12345"]', 'number' => 0,],
            ['name' => 'greeting', 'heading' => 'Anrede', 'version' => $version, 'fix' => true, 'is_heading' => false, 'choose_keywords' => false, 'tpls' => '["Sehr geehrte Damen und Herren,", "Sehr geehrte Frau Musterfrau,", "Sehr geehrter Herr Mustermann,"]', 'number' => 1,],
            ['name' => 'awareofyou', 'fix' => false, 'is_heading' => false, 'choose_keywords' => false, 'heading' => 'Wie bist du af die Stelle aufmerksam geworden?', 'version' => $version, 'tpls' => '["mit großem Interesse bin ich im XING Stellenmarkt auf die ausgeschriebene Position aufmerksam geworden. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).", "auf der Suche nach einer neuen Beschäftigung bin ich auf Ihr Unternehmen aufmerksam geworden und komme jetzt initiativ auf Sie zu. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).", "wie telefonisch besprochen, interessiere ich mich sehr für eine Beschäftigung in Ihrem Unternehmen. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w)."]', 'number' => 2,],
            ['name' => 'currentactivity', 'fix' => false, 'is_heading' => false, 'choose_keywords' => false, 'heading' => 'Was ist deine derzeitige Beschäftigung?', 'version' => $version, 'tpls' => '["Zurzeit arbeite ich als Musterberuf bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.", "Zurzeit studiere ich Musterstudiengang an der Musterhochschule. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.", "Zurzeit befinde ich mich in der Ausbildung als Musterausbildung bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.", "Zurzeit absolviere ich ein Praktikum im Bereich Musterstelle bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.", "Zurzeit besuche ich die Musterschule in Musterort. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen."]', 'number' => 3,],
            ['name' => 'whycontact', 'fix' => false, 'is_heading' => false, 'choose_keywords' => false, 'heading' => 'Warum bewirbst du dich bei dem Unternehmen?', 'version' => $version, 'tpls' => '["Ihr Stellenangebot hört sich toll an! Ich hoffe, mir hierdurch persönliche und fachliche Entwicklungsmöglichkeiten erschließen zu können. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.", "Nachdem ich schon länger in diesem Bereich tätig bin, suche ich jetzt nach einer neuen Position, in der ich mehr Verantwortung übernehmen kann. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.", "Mein Wunsch ist es, die beschriebene Aufgabenstellung als nächsten Schritt für meine weitere berufliche Entwicklung in Ihrem Hause zu nutzen. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.", "Ich suche an meinem neuen Wohnort eine interessante Beschäftigung und bin daher auf Ihr Unternehmen aufmerksam geworden. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen."]', 'number' => 4,],
            ['name' => 'workAndSkills', 'fix' => false, 'is_heading' => false, 'choose_keywords' => true, 'heading' => null, 'tpls' => '["In eine neue Aufgabe bei Ihnen kann ich verschiedene Stärken einbringen. So bin ich meine Aufgaben sehr", "angegangen. Mit mir gewinnt Ihr Unternehmen einen Mitarbeiter, der", "ist. Außerdem habe ich in früheren Projekten insbesondere ausgeprägte Kommunikationsstärke, hohe Lernbereitschaft und viel Kreativität unter Beweis stellen können."]', 'version' => $version, 'number' => 5,],
            ['name' => 'ending', 'fix' => true, 'is_heading' => false, 'choose_keywords' => false, 'heading' => 'Schlusswort', 'version' => $version, 'tpls' => '["Konnte ich Sie mit dieser Bewerbung überzeugen? Ich bin für einen Einstieg zum nächstmöglichen Zeitpunkt verfügbar. Einen vertiefenden Eindruck gebe ich Ihnen gerne in einem persönlichen Gespräch. Ich freue mich über Ihre Einladung!", "Ich danke Ihnen für das Interesse an meiner Bewerbung. Zum nächstmöglichen Zeitpunkt bin ich verfügbar. Wenn Sie mehr von mir erfahren möchten, freue ich mich über eine Einladung zum Vorstellungsgespräch.", "Ich hoffe, dass Sie einen ersten Eindruck von mir gewinnen konnten. Ein Einstieg zum nächstmöglichen Zeitpunkt ist für mich möglich. Ich freue mich, weitere Details und offene Fragen in einem persönlichen Gespräch auszutauschen."]', 'number' => 6,],
        ]);
        
        $ws_id = DB::table($table)->select('id')->where('version', $version)->where('name', 'workAndSkills')->first()->id;
        
        DB::table($kw_table)->insert([
            ['tpl_id' => $ws_id, 'number' => 0, 'heading' => 'Was zeichnet deine Arbeitsweise aus?', 'conjunction' => 'und', 'tpls' => '["zuverlässig", "verantwortungsbewusst", "präzise", "engagiert", "gewissenhaft", "ausdauernd"]',],
            ['tpl_id' => $ws_id, 'number' => 1, 'heading' => 'Welche Begriffe bezeichnen am besten deine persönlichen Kompetenzen?', 'conjunction' => 'und', 'tpls' => '["flexibel", "motiviert", "teamorientiert", "belastbar", "selbsständig", "aufgeschlossen", "begeisterungsfähig"]',],
        ]);
    }
    
    public function keywords() {
        return $this->hasMany(KeywordTemplate::class, 'tpl_id')->orderBy('number');
    }
}
