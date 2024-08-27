<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id'
    ];


    public function url()
    {
        return $this->hasMany(Url::class, 'user_id', 'user_id');
    }




    public function userid()
    {
        return $this->hasOne(Userid::class, 'user_id', 'user_id');
    }

 
  
    public function getCreatedDate()
    {
        // if ($this->user_status == 1) {
        //     return $this->created_at->format('Y-m-d H:i:s');
        // }
        return $this->created_at->format('Y-m-d H:i:s');
    }
    public function generatedUrlCount()
    {
        return Url::where("user_id", $this->user_id)->count();
    }
    public function getStatus()
    {
        return Status::find($this->userid->user_status)->status_text;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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
}
