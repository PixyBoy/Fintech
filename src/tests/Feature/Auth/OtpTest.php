<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Modules\Auth\Application\Jobs\SendOtpSms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::clear();
    }

    private function getCodeFromQueue(): string
    {
        $pushed = Queue::pushed(SendOtpSms::class);
        return $pushed[0]->code;
    }

    public function test_request_otp_dispatches_job(): void
    {
        Queue::fake();
        $res = $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        $res->assertStatus(200);
        Queue::assertPushed(SendOtpSms::class, 1);
    }

    public function test_throttle_phone_ip(): void
    {
        Queue::fake();
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        }
        $res = $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        $res->assertStatus(429);
    }

    public function test_verify_wrong_code_and_lockout(): void
    {
        Queue::fake();
        $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        for ($i = 0; $i < 5; $i++) {
            $resp = $this->postJson('/auth/verify-otp', ['phone' => '09120000000', 'code' => '000000']);
            $resp->assertStatus(422);
        }
        $resp = $this->postJson('/auth/verify-otp', ['phone' => '09120000000', 'code' => '000000']);
        $resp->assertStatus(422);
    }

    public function test_verify_success_creates_user_and_login(): void
    {
        Queue::fake();
        $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        $code = $this->getCodeFromQueue();
        $res = $this->postJson('/auth/verify-otp', ['phone' => '09120000000', 'code' => $code]);
        $res->assertStatus(200);
        $this->assertDatabaseHas('users', ['phone' => '09120000000']);
        $this->assertAuthenticated();
    }

    public function test_existing_user_login_without_duplicate(): void
    {
        $user = User::factory()->create();
        $user->phone = '09120000000';
        $user->save();
        Queue::fake();
        $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        $code = $this->getCodeFromQueue();
        $this->postJson('/auth/verify-otp', ['phone' => '09120000000', 'code' => $code])->assertStatus(200);
        $this->assertEquals(1, User::where('phone', '09120000000')->count());
    }

    public function test_kyc_level1_submit_creates_pending_record(): void
    {
        Queue::fake();
        $this->postJson('/auth/request-otp', ['phone' => '09120000000']);
        $code = $this->getCodeFromQueue();
        $this->postJson('/auth/verify-otp', ['phone' => '09120000000', 'code' => $code])->assertStatus(200);
        $res = $this->post('/kyc/level-1', [
            'name' => 'Ali',
            'family' => 'Reza',
            'national_code' => '1234567890',
        ]);
        $res->assertRedirect('/');
        $this->assertDatabaseHas('kyc_profiles', [
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);
    }
}
