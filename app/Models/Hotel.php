<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "hotels";
    protected $dates = ['deleted_at'];
    protected $fillable = [
       'name', 'user_id', 'count'
    ];
    protected $hidden = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reservations() {
        return $this->hasMany(HotelReservation::class)
        ->where([
            'status' => config('constants.hotel.RESERVED')
        ]);
    }

    public function proposes()
    {
        return $this->hasMany(HotelReservation::class)
        ->where([
            'status' => config('constants.hotel.PROPOSE')
        ]);
    }
}
