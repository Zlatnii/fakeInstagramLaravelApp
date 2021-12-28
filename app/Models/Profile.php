<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//nakon migriranja, potrebno je stvoriti vezu između tablica, dakle potrebno je spojiti
//$table->unsignedBigInteger('user_id');
//potrebno je riješiti da user ima jedan profil i profil pripada useru
//user() uvijek mora returnati relaciju, i pripada User::class
class Profile extends Model
{
    protected $guarded = [];
    //Nakon što smo došli ovdje za default image, radimo slijedeće:
    public function profileImage()
    {
        $imagePath = ($this->image) ? $this->image : '/profile/wK5u0R3cYsLexgj3kEKjK5JuWV8XztBuXFfMt76f.png';
        return '/storage/' . $imagePath;
    }
    //many to many relationships, znači kreirati prvo pivot table za followere, nakon toga otići u User.php
    //zatim ovdje
    public function followers()
    {
        return $this->belongsToMany(User::class);
    }
    //Ako ne učitava izmjene ovo je potrebno dodati
    //napravljena je relacija između profila i usera
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
