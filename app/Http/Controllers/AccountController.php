<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class AccountController extends Controller
{
    public function register(Request $request)
    {
       $request->validate([
           
            'password' => 'required',
            'email' => 'required|email|unique:users',
            'name' => 'required',
        ],[
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
        ]);
       

        $otp = Str::random(6);
        
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);
        Mail::send('email.otp', ['otp' => $otp], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject('Verifikasi Email');
    });



        return response()->json(['message' => 'OTP telah dikirim.',
    'status'=>200]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $otp = Otp::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP tidak valid.'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        $otp->delete();

        return response()->json(['message' => 'Email berhasil diverifikasi.'], 200);
    }
    

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
