<?php

namespace App\Models;
namespace App\Mail;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable. ->atributi koji se mogu masovno dodijeliti, znači;
     * name, email, username i password, što znači da ništa drugo neće biti punjivo, tj. neće se 
     * moći dodavati. To je samo jedan od protekcije koje laravel omogućuje, tako da ne smije ništa
     * drugo biti od onoga što je navedeno u bazi podataka.
     *      
     * @var array
     */
    //Model predstavlja red u našoj bazi podataka, 
    //znači da jedan od ovih 
    //User klada predstavlja jedan red u bazi podataka
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Ovime kreiramo profile

    protected static function boot()
    {
        parent::boot(); 
        //ovaj static event se aktivira svaki puta kada se novi profil kreira
        //nakon što ovo napravimo, moći ćemo u index-u promijeniti u hrefu harcodeanu sliku
        static::created(function($user){
            $user->profile()->create([
                'title' => $user->username
            ]);

            //Ovako radimo za slanje maila (mailtrap.io)
            //obratiti pažnju na .env datoteku te kopirati podatke sa mailtrap.io, a pritom namjestiti za laravel
            
            Mail::to($user->email)->send(new NewUserWelcomeMail());

        
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC'); 
        
        //znači da user može imati više postova, tako da se poziva klasa Post, koja je u biti model Post
        //->orderBy('created_at', 'DESC') -> ovime sortiramo postove prema created_at, u ovome slučaju u obrnutom smjeru, tako da najnoviji budu na vrhu 
        //'created_at' je u biti timestamp unutar migracija
    }
    
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }
    
    public function profile()
    {
        return $this->hasOne(Profile::class); //user pripada klasi User, a user ima jedan profil
    }                                         //nakon toga možemo ići u home.blade.php i urediti redove

    
}
