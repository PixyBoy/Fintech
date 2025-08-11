<?php

namespace App\Modules\Auth\Application\Http\Controllers;

use App\Models\User;
use App\Modules\Auth\Application\Events\UserLoggedIn;
use App\Modules\Auth\Application\Services\OtpService;
use App\Modules\Auth\Application\Http\Requests\RequestOtpRequest;
use App\Modules\Auth\Application\Http\Requests\VerifyOtpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class AuthController
{
    public function __construct(private OtpService $otp)
    {
    }

    public function requestOtp(RequestOtpRequest $request)
    {
        $this->otp->requestCode($request->input('phone'), $request->ip());
        return response()->json(['status' => 'ok']);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $phone = $request->input('phone');
        $code = $request->input('code');
        $this->otp->verifyCode($phone, $code, $request->ip());
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $user = new User();
            $user->phone = $phone;
            $user->verification_level = 0;
            $user->name = $phone;
            $user->email = $phone.'@example.com';
            $user->password = bcrypt('secret');
            $user->save();
        }
        Auth::login($user);
        Event::dispatch(new UserLoggedIn($user));
        return response()->json(['status' => 'ok', 'redirect' => '/']);
    }

    public function kycLevel1Form()
    {
        return view('authmod::kyc.level1');
    }

    public function kycLevel1Submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'family' => 'required|string',
            'national_code' => 'required|string',
        ]);
        DB::table('kyc_profiles')->updateOrInsert(
            ['user_id' => $request->user()->id, 'level' => 1],
            [
                'status' => 'pending',
                'data_json' => json_encode($data),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        return redirect('/');
    }
}
