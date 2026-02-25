<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'is_baned'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function membership(){
        return $this->hasMany(MemberShip::class);
    }

    public function colocation(){
        return $this->belongsTo(Colocation::class, 'colocation_user')
        ->withPivot('role' , 'joined_at' , 'left_at')
        ->withTimestamps();
    }

    public function expensePaid(){

        return $this->hasMany(Expense::class, 'paid_by');
    }

    public function settlementsPaid(){
        return $this->hasMany(Settlement::class , 'payer_id');
    }

    public function settlementsReceived(){
        return $this->hasMany(Settlement::class , 'receiver_id');
    }


    public function invitation(){
        return $this->hasMany(Invitation::class, 'email', 'email');
    }

     public function reputations()
    {
        return $this->hasMany(Reputation::class);
    }


    //helper method
      public function isGlobalAdmin()
    {
        return $this->role === 'admin';
    }

    //is this a owener of specific coloc 


     public function isOwnerOf($colocationId)
    {

        return $this->memberships()
                    ->where('colocation_id', $colocationId)
                    ->where('role', 'owner')
                    ->exists();
    }

}

