<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisiRequest;
use App\Http\Resources\DivisiCollection;
use App\Http\Resources\DivisiResource;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : DivisiCollection
    {
        $divisi = Divisi::all();
        return new DivisiCollection($divisi);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DivisiRequest $request) : DivisiResource
    {
        $validatedData = $request->validated();
        $check_name = Divisi::where('name', $validatedData['name'])->first();
        if ($check_name) {
            return response()->json([
                'message' => 'Nama sudah digunakan'
            ], 400);
        }
        $divisi = Divisi::create($validatedData);
        return new DivisiResource($divisi);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id) : DivisiResource
    {
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return new DivisiResource($divisi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DivisiRequest $request, Int $id)
    {
        $validatedData = $request->validated();
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $divisi->update($validatedData);
        return new DivisiResource($divisi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $divisi->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
        
    }
}
