<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\HotelResource;
use App\Http\Resources\HotelCollection;
use App\Models\Hotel;

class HotelController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'can:isAdmin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return new HotelCollection(Hotel::paginate(15));
        return $this->respond(new HotelCollection(Hotel::paginate(15)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'count' => ['required', 'integer'],
        ]);

        if ($validate->fails()) return $this->respondBadRequest($validate->errors());

        $hotel = Hotel::create([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'count' => $request->input('count')
        ]);

        return $this->respond(new HotelResource($hotel));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return $this->respond(new HotelResource(Hotel::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'count' => ['required', 'integer'],
        ]);

        if ($validate->fails()) return $this->respondBadRequest($validate->errors());

        $hotel = Hotel::findOrFail($id);
        $hotel->name = $request->input('name');
        $hotel->count = $request->input('count');
        $hotel->save();

        return $this->respond(new HotelResource($hotel));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return $this->respondNoContent();
    }
}
