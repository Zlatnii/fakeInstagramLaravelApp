@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-8">
            <img src="/storage/{{ $post->image }}" class=w-100 alt="">
        </div>
        <div class="col-4">
            <div>
            <div class="d-flex align-items-center">
                <div class=pr-3>   
                <!-- Ovo dodajemo kako bi nam izgledalo kao na instagramu kada kliknemo -->
                <!-- izradit ćemo specijalnu metodu kako bismo napravili default image, te otići na 
                Profile.php model  -->

                        <img src="{{ $post->user->profile->profileImage() }}" class=" rounded-circle w-100" style="max-width: 40px">
                </div>  
                <div>
                        <div class="font-weight-bold">
                            <a href="/profile/{{$post->user->id}}">
                                <span class="text-dark">{{ $post->user->username }}</span>
                            </a>
                            <a href="#" class="pl-3">Follow</a>
                        </div>
                </div>
            </div>  
        </div>

        <hr>

            <p>
                <span class="font-weight-bold">
                    <a href="/profile/{{$post->user->id}}">
                        <span class="text-dark">{{ $post->user->username }}</span>
                    </a>
                </span> 
                {{$post->caption}}
            </p>

        </div>
    </div>
</div>
@endsection
