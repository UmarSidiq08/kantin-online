<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanteenBlock extends Model
{
    protected $fillable = ['canteen_id', 'user_id'];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
