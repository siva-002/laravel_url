<?php

namespace App\Models;
use App\Models\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userid extends Model
{
    use HasFactory;

    protected $fillable = ["user_id"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function getStatus()
    {

        return Status::find($this->user_status)->status_text;
    }
    public function generatedUrlCount()
    {
        return Url::where("user_id", $this->user_id)->count();
    }
    public function getEmail()
    {
        // dd($this->user->email);
        return optional($this->user)->email ?? 'Not Available';
    }
    public function getName()
    {
        // dd($this->user->email);
        return optional($this->user)->name ?? 'Not Available';
    }

    public function getCreatedDate()
    {
        if ($this->user_status == 1) {
            return $this->created_at->format('Y-m-d H:i:s');
        }
        return $this->user->created_at->format('Y-m-d H:i:s');
    }


}
