<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Http\Resources\CutiCollection;
use App\Http\Resources\CutiResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : CutiCollection
    {
        $data = Cuti::all();

        return new CutiCollection($data);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCutiRequest $request) : CutiResource
    {
        $validated = $request->validated();

        $cuti = Cuti::create($validated);

        return new CutiResource($cuti);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id) : CutiResource
    {
        $cuti = Cuti::find($id);

        if (!$cuti) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
        );
        }


        return new CutiResource($cuti);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCutiRequest $request, Int $id) : CutiResource
    {
        $validated = $request->validated();

        $cuti = Cuti::find($id);

        if (!$cuti) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
            );
        }


        if (isset($validated['date_start'])) {
            $cuti->date_start = $validated['date_start'];
        }

        if (isset($validated['date_end'])) {
            $cuti->date_end = $validated['date_end'];
        }

        if (isset($validated['days'])) {
            $cuti->days = $validated['days'];
        }


        if (isset($validated['description'])) {
            $cuti->description = $validated['description'];
        }
        if (isset($validated['approve_hrd']) && $validated['approve_hrd'] == true) {
            // get auth id
            $user = User::where('id',  auth()->user()->id)->first();

            if ($user->jabatan->name == 'Maneger HRD') {
                $validated['approve_hrd'] = true;
            } else {
                $validated['approve_hrd'] = false;
            }

            $cuti->approve_hrd = $validated['approve_hrd'];
        }

        if (isset($validated['approve_atasan']) && $validated['approve_atasan'] == true) {
            // get auth id
            $user = User::where('id',  auth()->user()->id)->first();

            if ($user->jabatan->atasan == true) {
                $validated['approve_atasan'] = true;
            } else {
                $validated['approve_atasan'] = false;
            }

            $cuti->approve_atasan = $validated['approve_atasan'];
        }


        $cuti->save();

        return new CutiResource($cuti);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $cuti = Cuti::find($id);

        if (!$cuti) {
            throw new HttpResponseException(
                response()->json(['message' => 'Not Found'], 404
            )
            );
        }

        $cuti->delete();

        return response()->json(['message' => 'Success'], 200);
    }
}
