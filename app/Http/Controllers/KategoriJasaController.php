<?php

namespace App\Http\Controllers;

use App\Models\KategoriJasa;
use Illuminate\Http\Request;

class KategoriJasaController extends Controller
{
    // crud kategori jasa
    public function index()
    {
        $kategori_jasa = KategoriJasa::all();
        if($kategori_jasa->isEmpty()){
            return response()->json(['message' => 'data tidak di temukan','status'=>404]);
        }
        return response()->json(['message' => 'success data berhasil di tampilkan', 'data' => $kategori_jasa,'status'=>200]);
    }
    // post
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);
        $kategori_jasa = KategoriJasa::create($request->all());
        return response()->json(['message' => 'success data berhasil di simpan', 'data' => $kategori_jasa,'status'=>200]);
    }
    // get by id
    public function show($id)
    {
        $kategori_jasa = KategoriJasa::find($id);
        if($kategori_jasa == null){
            return response()->json(['message' => 'data tidak di temukan','status'=>404]);
        }
        return response()->json(['message' => 'success data berhasil di tampilkan', 'data' => $kategori_jasa,'status'=>200]);
    }
    // update
    public function update(Request $request, $id)
    {
        $kategori_jasa = KategoriJasa::find($id);
        if($kategori_jasa == null){
            return response()->json(['message' => 'data tidak di temukan','status'=>404]);
        }
        $kategori_jasa->update($request->all());
        return response()->json(['message' => 'success data berhasil di ubah', 'data' => $kategori_jasa,'status'=>200]);
    }
    // delete
    public function destroy($id)
    {
        $kategori_jasa = KategoriJasa::find($id);
        if($kategori_jasa == null){
            return response()->json(['message' => 'data tidak di temukan','status'=>404]);
        }
        $kategori_jasa->delete();
        return response()->json(['message' => 'success data berhasil di hapus','status'=>200]);
    }
    
}
