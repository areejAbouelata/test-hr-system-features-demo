<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Mail\SendOtpMail;
use App\Services\SmsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\OtpRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forget_password');
    }

    public function sendOtp(OtpRequest $request)
    {
        $user = User::where($request->otp_key, $request->user_name)->first();
        if (!$user) {
           return  redirect()->back()->withErrors('user not found');
        }

        $uuid = Uuid::uuid4()->toString();
        $otpCode = substr(preg_replace('/[^0-9]/', '', $uuid), 0, 4);
        $user->update(['otp' => $otpCode]);

        if ($request->otp_key == 'phone') {
            SmsService::send($user->phone, $otpCode);
        } else {
            Mail::to($user->email)->send(new SendOtpMail($otpCode));
        }

        return redirect()->route('auth.verify_otp_form', $request->user_name);
    }


    public function showVerifyOtpForm($user_name)
    {
        return view('auth.verify', compact('user_name'));
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        dd($request->all());
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->user_name)->orWhere('phone', $request->user_name);
        })
            ->where('otp', $request->otp)
            ->first();

        dd($user);
        if ($user) {
            return view('auth.verify');
        }

    }



}
