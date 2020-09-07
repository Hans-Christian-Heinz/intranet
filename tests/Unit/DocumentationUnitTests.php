<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DocumentationUnitTests extends TestCase
{
    use RefreshDatabase;

    /**
     * Stelle sicher, dass die Testdatenbank (sqlite) verwendet wird.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertEquals(env('DB_CONNECTION'), 'sqlite_test');
    }
}
