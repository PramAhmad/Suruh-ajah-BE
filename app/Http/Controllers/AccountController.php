<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class AccountController extends Controller
{
    public function register(Request $request)
    {
       $request->validate([
            'phone' => 'required|unique:users',
            'password' => 'required',
            'email' => 'required|email|unique:users',
            'name' => 'required',
        ],[
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
        ]);
       

        $otp = Str::random(6);

        Otp::create([
            'phone' => $request->phone,
            'otp' => $otp,
            'is_valid' => true,
        ]);
        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
    
        $message = $twilio->messages->create(
            $request->phone,
            [
                'from' => "+15416157447",
                'body' => $otp
            ]
        );


        return response()->json(['message' => 'OTP telah dikirim.',
    'status'=>200]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required',
        ]);
    
        $otp = Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();
    
        if (!$otp) {
            return response()->json(['message' => 'OTP tidak valid.', 'status' => 400], 400);
        }
 
        $user = User::where('phone', $request->phone)->first();
    
        $user->update(['phone_verified_at' => now()]);

        $otp->update(['is_valid' => 1]);

        $token = $user->createToken('authToken')->plainTextToken;
    
        return response()->json(['token' => $token, 'user' => $user], 200);
    }
    

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
            'type' => 'required|in:freelancer,user',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Login gagal.'], 401);
        }

        if (!$user->phone_verified_at) {
            return response()->json(['message' => 'Nomor telepon belum diverifikasi.'], 401);
        }
     
        if ($request->type == 'freelancer' && !$user->freelancer) {
            return response()->json(['message' => 'Anda bukan freelancer.'], 401);
        }
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}
