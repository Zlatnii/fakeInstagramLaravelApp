<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = []; //nakon ovog reda koda dešava se error 

    public function user()
    {
        return $this->belongsTo(User::class); //tako da ovaj model pripada modelu User
    }
}
