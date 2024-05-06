<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeDataResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\pendidikan;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : EmployeeCollection
    {
        $Employee = Employee::query()->where(function(Builder $query) use ($request) {
            

            $name = $request->input('name');
            if ($name) {
                $query->where('name', 'like', "%$name%");
            }
        });


        $Employee = $Employee->get();
        return new EmployeeCollection($Employee);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStoreRequest $request) : EmployeeResource
    {

        $validated = $request->validated();

        if (!isset($validated['user_id'])) {
            $user = auth()->user();
            $validated['user_id'] = $user->id;
        }
        $Employee = Employee::create($validated);
        return new EmployeeResource($Employee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id) : EmployeeDataResource
    {
        $employee = Employee::find($id);

        if(!$employee) {
            throw new HttpResponseException(response([
                "errors" => "Employee not found"
            ], 404));
        }

        return new EmployeeDataResource($employee);
    }


    public function me() : EmployeeResource
    {
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        if(!$employee) {
            throw new HttpResponseException(response([
                "errors" => "Employee not found"
            ], 404));
        }

        return new EmployeeResource($employee);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, Int $id) : EmployeeDataResource
    {
        $validated = $request->validated();
        $employee = Employee::find($id);

        if(!$employee) {
            throw new HttpResponseException(response([
                "errors" => "Employee not found"
            ], 404));
        }

        if ($employee->user_id != auth()->user()->id) {
            throw new HttpResponseException(response([
                "errors" => "Unauthorized"
            ], 401));
        }

        if (isset($validated['pendidikan']) && isset($validated['jenjang_pendidikan'])) {
            $pendidikan = Pendidikan::firstOrCreate([
                'jenjang_pendidikan' => $validated['jenjang_pendidikan']
            ]);
        
            $validated['pendidikan_id'] = $pendidikan->id;
        }

        $employee->update($validated);
        return new EmployeeDataResource($employee);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $id)
    {
        $employee = Employee::find($id);

        if(!$employee) {
            throw new HttpResponseException(response([
                "errors" => "Employee not found"
            ], 404));
        }

        if ($employee->user_id != auth()->user()->id) {
            throw new HttpResponseException(response([
                "errors" => "Unauthorized"
            ], 401));
        }

        $employee->delete();
        return response(null, 204);
    }
}
