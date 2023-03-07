<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Resources\HotelResource;
use App\Http\Resources\HotelCollection;
use App\Models\Hotel;

class HotelController extends ApiController
{
    public function __construct()
    {
        // 
        $this->middleware(['auth:sanctum', 'can:isAdmin'])->except(['index', 'show', 'reservation']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return new HotelCollection(Hotel::with('reservations')->orderBy('id', 'desc')->paginate(15));
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
        $hotel = Hotel::with('reservations')->findOrFail($id);
        return $this->respond(new HotelResource($hotel));
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

        return $this->show($hotel->id);
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

    public function reservation(Request $request, string $id)
    {
        //
        $hotel = Hotel::with('reservations')->findOrFail($id);
        if (count($hotel->reservations) >= $hotel->count) {
            return $this->respondBadRequest('SOLD OUT');
        }

        $hotel->reservations()->create([
            'status' => config('constants.hotel.PROPOSE'),
            'user_id' => auth()->user()->id,
        ]);

        return $this->show($hotel->id);
    }

    public function proposes(Request $request, string $id)
    {
        //
        $hotel = Hotel::with('proposes')->findOrFail($id);
        return $this->respond(new HotelResource($hotel));
    }

    public function approve(Request $request, string $id, $resevationId)
    {
        //
        $validate = Validator::make($request->all(), [
            'status' => [
                'required',
                'string',
                Rule::in(array_values(config('constants.hotel')))
            ]
        ]);
        if ($validate->fails()) return $this->respondBadRequest($validate->errors());

        $hotel = Hotel::findOrFail($id);
        $propose = $hotel->proposes()->findOrFail($resevationId);
        $propose->status = $request->input('status');
        $propose->save();

        return $this->show($hotel->id);
    }
}
