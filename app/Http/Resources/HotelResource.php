<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->count,
            'status' => (function () {
                $ret = config('constants.hotel.NORMAL');
                if ($this->count <= count($this->reservations)) {
                    $ret = config('constants.hotel.SOLDOUT');
                }
                return $ret;
            })(),
            'reserved_count' => $this->when(
                $this->reservations,
                count($this->whenLoaded('reservations'))
            ),
            'reservations' => HotelReservationResource::collection(
                $this->whenLoaded('reservations')
            ),
            'proposes' => HotelReservationResource::collection(
                $this->whenLoaded('proposes')
            ),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
