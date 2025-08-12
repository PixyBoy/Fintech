<?php

namespace Tests\PayForMe;

use App\Modules\PayForMe\Application\DTOs\CreateRequestInput;
use App\Modules\PayForMe\Application\UseCases\CreateRequest;
use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Repositories\PayForMeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Modules\PayForMe\Application\Services\Quote\QuoteCalculator;
use App\Modules\PayForMe\PayForMeServiceProvider;

class PayForMeCreateRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->register(PayForMeServiceProvider::class);
        $this->artisan('migrate', ['--path' => 'app/Modules/PayForMe/Database/Migrations', '--realpath' => true]);
        Storage::fake('public');
    }

    public function test_create_request_persists()
    {
        $user = \App\Models\User::factory()->create();
        $input = new CreateRequestInput($user->id, 'https://example.com', 10, null, [UploadedFile::fake()->create('f.pdf',100)]);
        $useCase = new CreateRequest(new PayForMeRepository(), new QuoteCalculator());
        $view = $useCase->execute($input);
        $this->assertNotEmpty($view->request_code);
        $this->assertDatabaseHas('payforme_requests', ['request_code' => $view->request_code]);
    }
}
