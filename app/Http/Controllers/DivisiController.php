<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisiStoreRequest;
use App\Http\Requests\DivisiUpdateRequest;
use App\Http\Resources\DivisiCollection;
use App\Http\Resources\DivisiResource;
use App\Http\Resources\UserResource;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : DivisiCollection
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        if ($request->input('query') ) {
            $divisi= Divisi::where('name', 'like', '%' . $request['query'] . '%')->orderby('id', 'asc');
        } else {
            $divisi = Divisi::orderBy('id', 'asc');
        }
        $divisi = $divisi->paginate(perPage: $size, page: $page);

        return new DivisiCollection($divisi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DivisiStoreRequest $request) : DivisiResource
    {
        $data = $request->validated();

        $divisi = Divisi::where('name', $request['name'])->first();
        if ($divisi) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Already Registered"
                    ]
                ]
            ], 400));
        }
        $divisi = new Divisi($data);
        return new DivisiResource($divisi);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id) : DivisiResource
    {
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Not Found"
                    ]
                ]
            ], 404));
        }

        return new DivisiResource($divisi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DivisiUpdateRequest $request, int $id)
    {
        $data = $request->validated();
        $divisi = Divisi::where('name', $request['name'])->first();
        if ($divisi) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Already Registered"
                    ]
                ]
            ], 400));
        }
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Not Found"
                    ]
                ]
            ], 404));
        }

        
        $divisi->name = $data['name'];
        $divisi->save();
        return new DivisiResource($divisi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $divisi = Divisi::where('id', $id)->first();
        if (!$divisi) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Not Found"
                    ]
                ]
            ], 404));
        }
        $jabatan = Jabatan::where("divisi_id", $id)->get();
        if ($jabatan->count() > 0) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Divisi Masih Memiliki Jabatan yang aktif"
                    ]
                ]
            ], 404));
        }
        $divisi->delete();
        return throw new HttpResponseException(response([
            "data" => [
                "message" => [
                    "Divisi Berhasil Dihapus"
                ]
            ]
        ], 200));
    }
}
