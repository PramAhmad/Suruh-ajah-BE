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
           
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'name' => 'required',
        ],[
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);
       

    //    number ot lenght 4
        $otp = mt_rand(1000, 9999);
        User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);
        Otp::create([
            'email' => $request->email,
            'otp' => $otp,
        ]);
        Mail::send('email.otp', ['otp' => $otp], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject('Verifikasi Email');

    });



        return response()->json(['message' => 'OTP telah dikirim.',
        'data' => [
            'email' => $request->email,
            'otp' => $otp,
        ],
    'status'=>201]);
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
        
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(
            ['message' => 'Email berhasil diverifikasi.',
            'token' => $token,
            'status'=>200
        ] );
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

        return response()->json(['token' => $token,'status'=>"200"]);
    }
}
