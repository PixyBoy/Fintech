<?php

namespace Tests\PayForMe;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Modules\PayForMe\PayForMeServiceProvider;

class PayForMeRequestFormFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->register(PayForMeServiceProvider::class);
        $this->artisan('migrate', ['--path' => 'app/Modules/PayForMe/Database/Migrations', '--realpath' => true]);
    }

    public function test_get_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('payforme.request.create'))
            ->assertStatus(200);
    }
}
