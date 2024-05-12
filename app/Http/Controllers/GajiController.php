<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Http\Requests\StoreGajiRequest;
use App\Http\Requests\UpdateGajiRequest;
use App\Http\Resources\GajiCollection;
use App\Http\Resources\GajiResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : GajiCollection
    {
        $data = Gaji::all();

        return new GajiCollection($data);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGajiRequest $request)
    {
        $validated = $request->validated();

        $gaji = Gaji::create($validated);

        return new GajiResource($gaji);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id) : GajiResource
    {
        $gaji = Gaji::find($id);

        if (!$gaji) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
        );
        }

        return new GajiResource($gaji);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGajiRequest $request, Int $id) : GajiResource
    {
        $gaji = Gaji::find($id);

        if (!$gaji) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
        );
        }

        $validated = $request->validated();

        $gaji->update($validated);

        return new GajiResource($gaji);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $gaji = Gaji::find($id);

        if (!$gaji) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
        );
        }

        $gaji->delete();

        return response()->json(['message' => 'Success'], 200);
    }
}
