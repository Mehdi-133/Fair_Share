<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberShip extends Model
{
   protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
}