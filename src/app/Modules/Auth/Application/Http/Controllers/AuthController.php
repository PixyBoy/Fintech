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
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function __construct(private OtpService $otp) {}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('flash_success', 'با موفقیت خارج شدید.');
    }
    public function requestOtp(RequestOtpRequest $request)
    {
        $phone = $request->input('phone');

        try {
            $this->otp->requestCode($phone, $request->ip());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors(), 'requestOtp')->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors(['phone' => 'ارسال کد ممکن نیست. بعداً تلاش کنید.'], 'requestOtp')->withInput();
        }

        session()->put('auth_phone', $phone);

        return back()
            ->with('otp_sent', true)
            ->with('flash_success', 'کد تایید ارسال شد.')
            ->withInput(['phone' => $phone]);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $phone = $request->input('phone');
        $code  = $request->input('code');

        try {
            $this->otp->verifyCode($phone, $code, $request->ip());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors(), 'verifyOtp')->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors(['code' => 'کد نادرست است یا منقضی شده.'], 'verifyOtp')->withInput();
        }

        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'verification_level' => 0,
                'name' => $phone,
                'email' => $phone.'@example.com',
                'password' => bcrypt('secret'),
            ]
        );

        Auth::login($user, true);
        Event::dispatch(new UserLoggedIn($user));
        session()->forget('auth_phone');

        return redirect()->intended('/')
            ->with('flash_success', 'ورود انجام شد.');
    }

    public function kycLevel1Form()
    {
        return view('auth::kyc.level1');
    }

    public function kycLevel1Submit(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string',
            'family'        => 'required|string',
            'national_code' => 'required|string',
        ]);

        DB::table('kyc_profiles')->updateOrInsert(
            ['user_id' => $request->user()->id, 'level' => 1],
            [
                'status'     => 'pending',
                'data_json'  => json_encode($data),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('home')
            ->with('flash_success', 'اطلاعات سطح ۱ ثبت شد و در انتظار بررسی است.');
    }
}
