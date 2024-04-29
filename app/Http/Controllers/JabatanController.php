<?php

namespace App\Http\Controllers;

use App\Http\Requests\JabatanStoreRequest;
use App\Http\Requests\JabatanUpdateRequest;
use App\Http\Resources\JabatanCollection;
use App\Http\Resources\JabatanResource;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JabatanCollection
    {
        $Jabatan = Jabatan::query()->where(function(Builder $query) use ($request) {
            

            $name = $request->input('name');
            if ($name) {
                $query->where('name', 'like', "%$name%");
            }
        });


        $produk = $Jabatan->get();
        return new JabatanCollection($produk);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JabatanStoreRequest $request) : JabatanResource
    {
        $data = $request->validated();

        // check if divisi exists
        $divisi = Divisi::find($data['divisi_id']);

        if (!$divisi) {
            throw new HttpResponseException(response([
                "message" => "Divisi tidak ditemukan"
            ], 404));
        }

        // check if same name exists
        $sameName = Jabatan::where('name', $data['name'])->first();

        if ($sameName) {
            throw new HttpResponseException(response([
                "message" => "Jabatan dengan nama yang sama sudah ada"
            ], 400));
        }

        // check if atasan exists

        if($data['atasan'] != 0){
            $atasan = Jabatan::where('divisi_id', $data['divisi_id'])->where('atasan', $data['atasan'])->first();
            if ($atasan) {
                throw new HttpResponseException(response([
                    "message" => "Atasan Pada Divisi Tersebut Sudah Ada"
                ], 404));
            }
        }
        $Jabatan = Jabatan::create($data);
        return new JabatanResource($Jabatan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id) : JabatanResource
    {
        $Jabatan = Jabatan::find($id);

        if (!$Jabatan) {
            throw new HttpResponseException(response([
                "message" => "Jabatan tidak ditemukan"
            ], 404));
        }

        return new JabatanResource($Jabatan);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(JabatanUpdateRequest $request, Int $id) : JabatanResource
    {

        $Jabatan = Jabatan::find($id);

        if (!$Jabatan) {
            throw new HttpResponseException(response([
                "message" => "Jabatan tidak ditemukan"
            ], 404));
        }

        $data = $request->validated();
        // dd($data);

        // check if divisi exists
        if (isset($data['divisi_id'])) {
            $divisi = Divisi::find($data['divisi_id']);

            if (!$divisi) {
                throw new HttpResponseException(response([
                    "message" => "Divisi tidak ditemukan"
                ], 404));
            }

            $Jabatan->divisi_id = $data['divisi_id'];
        }

        // check if same name exists
        if (isset($data['name'])) {
            $sameName = Jabatan::where('name', $data['name'])->where('id' , '!=', $Jabatan->id)->first();

            if ($sameName) {
                throw new HttpResponseException(response([
                    "message" => "Jabatan dengan name yang sama sudah ada"
                ], 400));
            }

            $Jabatan->name = $data['name'];
        }

        // check if atasan exists
        if (isset($data['atasan'])) {
            if($data['atasan'] != 0){
                $atasan = Jabatan::where('divisi_id', $data['divisi_id'])->where('atasan', $data['atasan'])->first();
                if ($atasan) {
                    throw new HttpResponseException(response([
                        "message" => "Atasan Pada Divisi Tersebut Sudah Ada"
                    ], 404));
                }
            }

            $Jabatan->atasan = $data['atasan'];
        }

        if(isset($data['validator'])){
            $Jabatan->validator = $data['validator'];
        }

        $Jabatan->save();
        return new JabatanResource($Jabatan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $Jabatan = Jabatan::find($id);

        if (!$Jabatan) {
            return response()->json([
                "message" => "Jabatan tidak ditemukan"
            ], 404);
        }

        $Jabatan->delete();
        return response()->json([
            "message" => "Jabatan berhasil dihapus"
        ]);

    }
}
