<?php

namespace App\Http\Controllers;

use App\Models\Freelancer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreelancerController extends Controller
{
    // daftar
    public function store(Request $request)
    {
        $request->validate([

            'ktp' => 'required|min:16|numeric',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:10048',
        ],[

            'ktp.required' => 'ktp harus diisi',
            'foto_ktp.required' => 'foto ktp harus diisi',
            'ktp.max' => 'ktp minimal 16 karakter',
            'ktp.numeric' => 'ktp harus berupa angka',

            'foto_ktp.image' => 'foto ktp harus berupa gambar',
            'foto_ktp.mimes' => 'foto ktp harus berupa gambar jpeg,png,jpg',
            'foto_ktp.max' => 'foto ktp maksimal 10mb',

        ]);
        
        if(Auth::user()->phone_verified_at == null){
            return response()->json(['message' => 'phone belum di verifikasi','status'=>400]);
        }
        $file = $request->file('foto_ktp');
        $nama_file = '-'.time().$file->getClientOriginalName();
    
        $tujuan_upload =public_path( '/upload/ktp');
        $file->move($tujuan_upload,$nama_file);

        $url = url('/upload/ktp/'.$nama_file);
     
        $freelancer = Freelancer::create([
            'user_id' => Auth::user()->id,
            'ktp' => $request->ktp,
            'foto_ktp' => $url,
        ]);
        return response()->json(['message' => 'success data berhasil di simpan', 'data' => $freelancer,'status'=>200]);
    }
    public function order(Request $request) {
        $request->validate([
            'jasa_id'
        ])
    }
}
