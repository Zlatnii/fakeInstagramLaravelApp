<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        //ovime dajemo potrebu za logiranjem kako bi se mogli pokrenuti slijedeći kodovi
        //znači da nećemo moći otići na /p/create bez da se logiramo
        $this->middleware('auth');
    }
    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');

        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

        return view('posts.index', compact('posts'));
    }

    //nakon što se provjeri u restfull 
    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        //u redu ispod smo pozvali varijablu $data, kojoj smo dali vrijednosti request()->validate([
        //tako da navedeno u zagradi bude potrebno, i da ispunjava uvjete
        //npr. u ovom slučaju, ne možemo umjesto dodavanja slike dodati dokument, nego striktno sliku    
        //])
       $data = request()->validate([
           'caption' => 'required',
           'image' => ['required', 'image'],

       ]);
        //u navedenom kodu ispod, znači da trebamo image, te pravimo putanju gdje da ju spremimo
        //u ovom slučaju ->store('uploads', 'public')); što znači da će ju spremiti u uploads direktorij,
        //bilo koja slika se uploada, ide u tu mapu
        //drugi parametar nam govori koji driver hoćemo koristiti pri spremanju naše datoteke
        //kada odemo na mapu sa lijeve strane "storage"->"public"->"uploads" možemo vidjeti našu datoteku
        //i kako bi se prikazala ova datoteka, potrebno je pokrenuti php artisan storage:link(link od datoteke)
        $imagePath = request('image')->store('uploads', 'public');
        //ovdje upisujemo za veličinu slike nakon instalacije komposera za slike
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);  
        $image -> save();

       auth()->user()->posts()->create([
        'caption' => $data['caption'],
        'image'   => $imagePath,
       ]); 
       //znači, potrebno je biti logiran (prijavljen korisnik), kako bi
       //se post mogao kreirati tj objaviti

       //\App\Models\Post::create($data); -> Tako da je najjednostavniji navedeni prethodni kod kako bi se slika objavila
        /*Iako smo mogli ovako napisati navedeni kod:
            \App\Models\Post::create([
                'caption' => $data['caption']
            ]);   
            Ali nema potrebe za tim jer je $data navedeno unutar create-a.
        */ 


        //ovime upućujemo gdje trebamo ići nakon što se image uploada
       return redirect('/profile/' . auth()->user()->id);
    }
    //također služi pri izradi posta na drugom tabu
    public function show(\App\Models\Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
