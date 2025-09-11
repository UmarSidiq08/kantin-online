<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = ['menu_id', 'user_id', 'order_id', 'rating', 'review_text'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
     public function order() {
        return $this->belongsTo(Order::class);
    }
}
