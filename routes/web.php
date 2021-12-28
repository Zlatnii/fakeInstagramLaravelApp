<?php

use Illuminate\Support\Facades\Route;
use App\Mail\NewUserWelcomeMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/email', function(){
    return new NewUserWelcomeMail();
});

Route::post('follow/{user}', [App\Http\Controllers\FollowsController::class, 'store']);

Route::get('/', [App\Http\Controllers\PostsController::class, 'index'] );
Route::get('/p/create', [App\Http\Controllers\PostsController::class, 'create']);
//Ruta iznad je za prikazivanje slike na drugom tabu
///p/create inače ne radi zbog ove dvije rute, jer su u konfliktu (/p/{post}, /p/create), u ovom slučaju
//potrebno je zamijeniti im mjesta, tako da /p/{post} bude ispod, a /p/create bude iznad
//jer /p/{post} hvata sve iza /p/, tako da zbog toga neće moći pustiti da create prođe
Route::get('/p/{post}', [App\Http\Controllers\PostsController::class, 'show']);
//nakon što smo dodali u create.blade.php formu i namjestili enctype="multipart/form-data" method="post"
//potrebno je dodati rutu get sa metodom store, te nakon toga idemo u PostsController i dodajemo novu metodu 
Route::post('/p', [App\Http\Controllers\PostsController::class, 'store']);

Route::get('/profile/{user}', [App\Http\Controllers\ProfilesController::class, 'index'])->name('profile.show'); 
//'index' je jednostavno kao ime metode "public function index()" iz ProfilesControllera
Route::get('/profile/{user}/edit', [App\Http\Controllers\ProfilesController::class, 'edit'])->name('profile.edit'); 
//'edit' ruta se dodaje nakon što smo u index-u dodali unutar href-a u Edit Profile-u 
// href = "/profile/{{ $user->id }}/edit" , naravno da je potrebno pripaziti na izmjene
Route::patch('/profile/{user}', [App\Http\Controllers\ProfilesController::class, 'update'])->name('profile.update');
//Ova ruta će odraditi proces, a prethodna će prikazati formu  (s time, da update još nemamo)
