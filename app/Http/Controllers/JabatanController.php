<?php

namespace App\Http\Controllers;

use App\Http\Requests\JabatanStoreRequest;
use App\Http\Requests\JabatanUpdateRequest;
use App\Http\Resources\JabatanCollection;
use App\Http\Resources\JabatanResource;
use App\Models\Jabatan;
use App\Models\User;
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
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $jabatan = Jabatan::query()->where(function (Builder $builder) use ($request){
            $name = $request['name'];
            if ($name) {
                $builder->where('name', 'like', '%' . $name . '%');
            }
            $divisi_id = $request['divisi_id'];
            if ($divisi_id) {
                $builder->where('divisi_id', $divisi_id);
            }
        });
        $jabatan = $jabatan->paginate(perPage: $size, page: $page);

        return new JabatanCollection($jabatan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JabatanStoreRequest $request) : JabatanResource
    {
        $data = $request->validated();
        $jabatan = Jabatan::where('name', $request['name'])->where('divisi_id', $request['divisi_id'])->first();
        if ($jabatan) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Already Registered"
                    ]
                ]
            ], 400));
        }
        if ($data['atasan'] == true) {
            $jabatan = Jabatan::where('atasan', true)->where('divisi_id', $request['divisi_id'])->first();
            if ($jabatan) {
                throw new HttpResponseException(response([
                    "errors" => [
                        "messege" => [
                            "Jabatan Atasan Already Registered"
                        ]
                    ]
                ], 400));
            }
        }
        $jabatan = new Jabatan($data);
        return new JabatanResource($jabatan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $int) : JabatanResource
    {
        $jabatan = Jabatan::where('id', $int)->first();
        if (!$jabatan) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Not Found"
                    ]
                ]
            ], 404));
        }

        return new JabatanResource($jabatan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JabatanUpdateRequest $request, Int $id) : JabatanResource
    {
        $data = $request->validated();
        $jabatan = Jabatan::where('id', $id)->first();
        if (!$jabatan) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Not Found"
                    ]
                ]
            ], 404));
        }

        if ($jabatan->name != $request['name'] || $jabatan->divisi_id != $request['divisi_id']) {
            $unique_check = Jabatan::where('name', $request['name'])->where('divisi_id', $request['divisi_id'])->first();
            if ($unique_check) {
                throw new HttpResponseException(response([
                    "errors" => [
                        "messege" => [
                            "Jabatan Already Registered"
                        ]
                    ]
                ], 400));
            }
        }
        
        if ($jabatan->atasan !== $request['atasan'] && $request['atasan'] == true ) {
            $check_atasan = Jabatan::where('atasan', true)->where('divisi_id', $request['divisi_id'])->first();
            if ($check_atasan) {
                throw new HttpResponseException(response([
                    "errors" => [
                        "messege" => [
                            "Jabatan Atasan Already Registered"
                        ]
                    ]
                ], 400));
            }
        }
        
        $jabatan->fill($data);
        $jabatan->save();
        return new JabatanResource($jabatan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $jabatan = Jabatan::where('id', $id)->first();
        if (!$jabatan) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Not Found"
                    ]
                ]
            ], 404));
        }
        $user = User::where('jabatan_id', $jabatan->id)->first();
        if ($user) {
            // dd($user);
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Still Have User"
                        ]
                        ]
                    ], 400));
        }
        
        $jabatan->delete();
        return response()->json([
            'data' => true
        ]);
    }
}
