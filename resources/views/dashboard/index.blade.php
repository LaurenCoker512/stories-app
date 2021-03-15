@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        @if(auth()->id() === $user->id)
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        @else
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
                    <div class="card mt-4" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">
                                {{ $story->description }}
                            </p>
                            <a href="{{ $story->firstChapterPath() }}" class="btn btn-dark">View</a>
                            @if(auth()->id() === $user->id)
                            <a href="{{ $story->path() }}/edit" class="btn btn-dark">Edit Story</a>
                            <form method="POST" action="{{ $story->path() }}">
                                @method('DELETE')
                                @csrf
                                <input 
                                    class="btn btn-dark" 
                                    type="submit" 
                                    value="Delete Story" 
                                    onclick="return confirm('Are you sure you want to delete this story?');">
                            </form>
                            @endif
                        </div>
                    </div>
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
            @endif
        </div>
    </section>

@endsection