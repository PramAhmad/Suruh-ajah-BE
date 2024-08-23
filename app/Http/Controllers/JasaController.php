<?php

namespace App\Http\Controllers;

use App\Models\Jasa;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jasas = Jasa::where('user_id', Auth::user()->id)->with("user","kategori_jasa")->get();
        if($jasas->isEmpty()){
            return response()->json(['message' => 'data not found','status'=>404]);
        }
        return response()->json(['message' => 'success', 'data' => $jasas,'status'=>200]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jasa' => 'required|string',
            'kategori_jasa_id' => 'required|exists:kategori_jasas,id',
            'deskripsi' => 'required|min:6',
            'kontak' => 'nullable',
            'alamat' => 'required|min:6',
            'harga' => 'required|numeric',
            'waktu' => 'nullable',
        ],[
            'nama_jasa.required' => 'nama jasa harus diisi',
            'kategori_jasa_id.required' => 'kategori jasa harus diisi',
            'kategori_jasa_id.exists' => 'kategori jasa tidak ditemukan',
            'deskripsi.required' => 'deskripsi harus diisi',
            'deskripsi.min' => 'deskripsi minimal 6 karakter',
            'alamat.required' => 'alamat harus diisi',
            'alamat.min' => 'alamat minimal 6 karakter',
            'harga.required' => 'harga harus diisi',
            'harga.numeric' => 'harga harus berupa angka',
        ]);
        $request['user_id'] = Auth::user()->id;

        $jasa = Jasa::create($request->all());
        return response()->json(['message' => 'success', 'data' => $jasa,'status'=>200]);
        
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jasa = Jasa::find($id)->with("user","kategori_jasa")->first();
        if($jasa == null){
            return response()->json(['message' => 'data not found','status'=>404]);
        }
        return response()->json(['message' => 'success', 'data' => $jasa,'status'=>200]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_jasa' => 'sometimes|required|string',
            'kategori_jasa_id' => 'sometimes|required|exists:kategori_jasas,id',
            'deskripsi' => 'sometimes|required|min:6',
            'kontak' => 'nullable',
            'alamat' => 'sometimes|required|min:6',
            'harga' => 'sometimes|required|numeric',
        ],[
            'nama_jasa.required' => 'nama jasa harus diisi',
            'kategori_jasa_id.required' => 'kategori jasa harus diisi',
            'kategori_jasa_id.exists' => 'kategori jasa tidak ditemukan',
            'deskripsi.required' => 'deskripsi harus diisi',
            'deskripsi.min' => 'deskripsi minimal 6 karakter',
            'alamat.required' => 'alamat harus diisi',
            'alamat.min' => 'alamat minimal 6 karakter',
            'harga.required' => 'harga harus diisi',
            'harga.numeric' => 'harga harus berupa angka',
        ]);
    
        $jasa = Jasa::find($id);
    
        if ($jasa == null) {
            return response()->json(['message' => 'data not found', 'status' => 404]);
        }
    
        $jasa->update($request->only([
            'nama_jasa',
            'kategori_jasa_id',
            'deskripsi',
            'kontak',
            'alamat',
            'harga',
        ]));
    
        return response()->json(['message' => 'success', 'data' => $jasa, 'status' => 200]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jasa = Jasa::find($id);
        if($jasa == null){
            return response()->json(['message' => 'data not found','status'=>404]);
        }
        $jasa->delete();
        return response()->json(['message' => 'success','status'=>200]);
    }
}
