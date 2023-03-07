<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelReservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "hotel_reservations";
    protected $dates = ['deleted_at'];
    protected $fillable = [
       'hotel_id', 'user_id', 'reserved_at', 'status'
    ];
    protected $hidden = ['deleted_at'];

    public function hotel(){
        return $this->belongsTo(Hotel::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
