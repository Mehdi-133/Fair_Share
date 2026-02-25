
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reputation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
