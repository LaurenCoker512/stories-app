@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        @if(auth()->id() === $user->id)
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
        @else
            <!-- Subscribe/Unsubscribe -->
            @if(auth()->id() && !$userIsSubscribed)
            <form method="POST" action="/subscriptions/user/{{ $user->id }}" class="d-inline-block float-right">
                @csrf
                <input 
                    class="btn btn-dark" 
                    type="submit" 
                    value="Subscribe">
            </form>
            @elseif(auth()->id() && $userIsSubscribed)
            <form method="POST" action="/subscriptions/user/{{ $user->id }}" class="d-inline-block float-right">
                @method('DELETE')
                @csrf
                <input 
                    class="btn btn-dark" 
                    type="submit" 
                    value="Unsubscribe">
            </form>
            @endif
            <h1>{{ $user->name }}'s Stories</h1>
        @endif
        <div class="row">
            @if(auth()->id() === $user->id)
            <div class="col-md-9 col-12">
                
                <h2>Your Stories</h2>

                <a href="/stories/create" class="btn btn-dark" role="button">Create a New Story</a>
            @else
            <div class="col-12">
            @endif

                @forelse($stories as $story)
                    <x-story :story="$story" :user="$story->user"/>
                @empty
                    @if(auth()->id() === $user->id)
                        <div class="mt-4">You haven't posted any stories yet.</div>
                    @else
                        <div class="mt-4">This user hasn't posted any stories yet.</div>
                    @endif
                @endforelse
            </div>
            @if(auth()->id() === $user->id)
            <div class="col-md-3 col-12">
                <h2>Your Subscriptions</h2>

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text font-weight-bold">
                            <a href="author-path" class="text-dark">Author</a>
                        </p>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">View</a>
                    </div>
                </div>

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="strong">Author</p>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">View</a>
                    </div>
                </div>

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="strong">Author</p>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">View</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

@endsection