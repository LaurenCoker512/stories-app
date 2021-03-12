@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        @if(auth()->id() === $user->id)
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        @else
        <h1>{{ $user->name }}'s Stories</h1>
        @endif
        <div class="row">
            <div class="col-9">
                @if(auth()->id() === $user->id)
                <h2>Your Stories</h2>

                <a href="#" class="btn btn-dark" role="button">Create a New Story</a>
                @endif

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">View</a>
                        @if(auth()->id() === $user->id)
                        <a href="#" class="btn btn-dark">Edit Story</a>
                        <form method="POST" action="/story-path">
                            @method('DELETE')
                            @csrf
                            <input class="btn btn-dark" type="submit" value="Delete Story">
                        </form>
                        @endif
                    </div>
                </div>

                @forelse($stories as $story)
                    <div class="card mt-4" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">
                                {{ $story->description }}
                            </p>
                            <a href="{{ $story->path() }}" class="btn btn-dark">View</a>
                            @if(auth()->id() === $user->id)
                            <a href="#" class="btn btn-dark">Edit Story</a>
                            <form method="POST" action="/story-path">
                                @method('DELETE')
                                @csrf
                                <input class="btn btn-dark" type="submit" value="Delete Story">
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div>There are no stories yet. Create one here!</div>
                @endforelse
            </div>
            <div class="col-3">
                <h2>Your Subscriptions</h2>

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
        </div>
    </section>

@endsection