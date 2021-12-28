<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{ //Many to many relationship je potreban kako bi followere osposobio, 
  //jer profil može imati mnogo followera,
  //i user može pratiti mnogo profila
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function store(User $user)
    { 
        return auth()->user()->following()->toggle($user->profile);
    }
}