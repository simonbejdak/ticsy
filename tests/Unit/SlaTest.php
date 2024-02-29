<?php


use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_slable()
    {
        // Incident is SLAble, as per its userConfiguration
        $slable = Incident::factory()->create();
        $sla = $slable->sla;

        $this->assertEquals($slable->id, $sla->slable->id);
    }
}
