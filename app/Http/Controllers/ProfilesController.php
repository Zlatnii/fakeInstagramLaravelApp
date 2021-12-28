<?php

namespace App\Http\Controllers;

use App\Models\User; //potrebno je dodati "Models" između, a model predstavlja jedan red unutar tablice

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image; //importati kako bi kod za image funkcionirao

class ProfilesController extends Controller
{
    public function index(\App\Models\User $user)
    {
                //ovo smo skratili kako bi sa kontrolera koristili u indexu

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        //imamo $postCount koji je jednak Cache(kešu) koji će zapamtiti 
        $postCount = Cache::remember(
            //te će koristiti ključnu riječ count(zbrajaj).posts(postove) .(točka) nije bitno koji je user id
            'count.posts.' . $user->id,
            //spremit ćemo podatak trenutno plus 30 sekundi
            now()->addSeconds(30), 
            //ako nije podatak ondje, ovo će se pokrenuti
            function() use ($user) {
                return $user->posts->count();
            });

            $followersCount = Cache::remember(
                'count.followers.' . $user->id,
                now()->addSeconds(30), 
                function() use ($user) {
                    return $user->profile->followers->count();
                });
            
            $followingCount = Cache::remember(
                'count.following.' . $user->id,
                now()->addSeconds(30), 
                function() use ($user) {
                    return $user->following->count();
                });

        //$followingCount = $user->following->count(); ovako je bilo za sve tri prethodne stavke navedeno 
        
        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
                //Ovo ćemo prebaciti u jednostavniju funkciju sa compact funkcijom, također i donji dio
                //zakomentirani je stari oblik, dok je ovaj prethodni jednostavniji i čišći
                // $user = User::findOrFail($user);
                // return view('profiles.index', [          //'user' je jednak varijabli $user,
                //     'user' => $user,                     //'user' će biti unutar našeg home.blade.php
                // ]); 
                //nema potrebe za pisanjem home.blade.php,
                //jer laravel već zna da se nalazi u mapi 
                //funkcija kopirana sa HomeController.php
    }

    public function edit(\App\Models\User $user)
    {   
        //Nakon što dodamo u ProfilePolicy, ovo dodajemo ovdje
        //Ovime zaštićujemo stavke od uređivanja od druge strane
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }
    //Nakon uređenja, potrebno je napraviti funkciju update (služi za uređenje profila)
    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);
       
        
        if(request('image'))
        { 
        //Kopirano iz PostsController.php
        //Izmijenjeno je u storeu 'profile'
        $imagePath = request('image')->store('profile', 'public');
        //ovdje upisujemo za veličinu slike nakon instalacije komposera za slike
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);  
        $image -> save(); 
        
        $imageArray = ['image' => $imagePath];
        }
       //auth()->user()->profile->update($data); ovo će se ispraviti kodom iznad
       // auth()->user()->fill($data);
       // auth()->user()->save();
            //potrebno je kreirati novu policu php artisan make:policy
            //php artisan make:policy ProfilePolicy -m Profile
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));  
       
        return redirect("/profile/{$user->id}"); 
    } 
    
    
}