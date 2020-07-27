<?php

namespace App\Console\Commands;

use App\Documentation;
use App\Proposal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearDocumentLocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locks:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gebe alle Dokumente (Anträge und Dokumentationen) frei, die nicht innerhalb der ' .
        'letzten 30 Minuten bearbeitet wruden.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Gebe alle Dokumente frei, die nicht innerhalb der letzten 30 Minuten bearbeitet wurden.
        $now = Carbon::create('now');
        foreach(array_merge(Documentation::all()->all(), Proposal::all()->all()) as $document) {
            $update = Carbon::instance($document->updated_at);
            if ($now > $update->addMinutes(30)) {
                $document->lockedBy()->associate(null);
                $document->save();
            }
        }
    }
}
