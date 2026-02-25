
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = [

        'name',
        'description',
        'status'

    ];


    public function  user(){

        return $this->belongsToMany(Colocation::class , 'colocation_user')
        ->withPivot('role' , 'joined_at' , 'left_at')
        ->withTimestamps();
    }

    

    public function membership(){
        return $this->hasMany(MemberShip::class);
    }

    public function expense(){
        return $this->hasMany(Expense::class);
    }

    public function Category(){
        return $this->hasMany(Category::class);
    }

    public function invitation(){
        return $this->hasMany(Invitation::class);
    }


}
