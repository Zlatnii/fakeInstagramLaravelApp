@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 pb-5">
        <!-- potrebno je promijeniti sliku, jer je hardcodana,a to radimo u ProfilesController.php -->
            <!-- potrebno je voditi brigu jer smo /storage/ uklonili sa ancer tagova -->
            <img src="{{ $user->profile->profileImage() }}" class="rounded-circle w-100">
        </div> 
            <div class="col-9 pt-5">
                <div class="d-flex justify-content-between align-items-baseline">
                    <div class="d-flex align-items-center pb-3">
                        <div class="h4">{{ $user->username }}</div>
                        <!-- nakon što smo button prebacili u FollowButton.vue, i follow-button prebacili ovdje,
                        potrebno je pokrenuti npm run watch -->
                        <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                    </div>

                        @can('update', $user->profile)
                        <a href="/p/create">Add new post</a>
                        @endcan

                </div>

                @can('update', $user->profile)
                <!-- pišemo kod iznad u slučaju kako bi maknuli link kojime uređujemo profil -->
                    <a href="/profile/{{ $user->id }}/edit">Edit Profile</a>
                @endcan
                <!--kako bi zamijenili ovo potrebna nam je baza, 
                a do baze dolazimo pomoću kontrolera. 
                Pozovemo navedenu varijablu iz ProfilesaContrroller.php-->
               

                <div class="d-flex">
                    <div class="pr-5"><strong>{{ $postCount }}</strong> posts</div>
                    <div class="pr-5"><strong>{{ $followersCount }}</strong> followers</div>
                    <div class="pr-5"><strong>{{ $followingCount }}</strong> following</div>
                </div>
                <div class="pt-4 font-weight-bold">{{ $user->profile->title ?? 'N/A' }}</div> 
                <!--$user->profile to će returnati profil od usera, 
                a profle->title, to će fetchati title sa profila od usera-->
                <div>{{ $user->profile->description ?? 'N/A' }}</div>
                <div><a href="#"> {{ $user->profile->url ?? 'N/A' }} </a></div>
            </div>
            <div class="row pt-5">
            @foreach($user->posts as $post)<!-- $user ->(daj mi sve postove)posts as (kao) $post -->

                <div class="col-4 pb-4">
                <!-- Otići na laravel controller documentation i vidjeti u tablici kako doći do
                storea, edita, create i show-a.
                Ovo ispod služi kada se klikne na sliku da se otvori i prikaže na drugom tabu, nakon
                ovoga idemo u web.php-->
                    <a href="/p/{{ $post->id }}">
                        <img src="/storage/{{ $post->image }}" class="w-100">
                    </a>
                </div>
            @endforeach
            </div>  
    </div>
</div>
@endsection
<!-- composer require intervention/image -> php libary koji se instalira kako bi u ovom slučaju slika
bila kvadratnog oblika i jednaka, tj da slika nije jedna veća, a druga manja. -->