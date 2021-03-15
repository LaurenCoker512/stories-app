@extends('layouts.app')

@section('content')
    <div class="jumbotron" style="background-image: url('https://miro.medium.com/max/2400/0*5dZpxRIiURrp8obS'); background-repeat: no-repeat; background-position: center; background-size: cover;">
        <h1 class="display-4 text-white text-center" style="text-shadow: black 0.04em 0.04em 0.04em; font-family: 'Dancing Script', cursive;">Welcome to Stories!</h1>
        <p class="lead text-white text-center" style="text-shadow: black 0.05em 0.05em 0.05em;">We're an archive of all sorts of writing, from fiction to nonfiction to poetry.</p>
        <p class="lead text-white text-center">
            <a class="btn btn-outline-light btn-lg" href="#" role="button">Create a Story</a>
        </p>
    </div>

    <section class="container mb-4">
        <div class="row">
            <div class="col-md-9 col-12">
                <h2>Latest Stories</h2>

                @forelse($stories as $story)
                    <div class="card mt-4" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">
                                {{ $story->description }}
                            </p>
                            <a href="{{ $story->firstChapterPath() }}" class="btn btn-dark">Read more</a>
                        </div>
                    </div>
                @empty
                    <div>There are no stories yet. Create one here!</div>
                @endforelse
            </div>
            <div class="col-md-3 col-12">
                <h2>Popular Tags</h2>
                <ul class="list-group mt-4 mb-4">
                    @foreach($tags as $tag)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ $tag->path() }}" class="text-dark">{{ $tag->name }}</a>
                        <span class="badge badge-dark badge-pill">{{ $tag->story_count }}</span>
                    </li>
                    @endforeach
                </ul>
                
                <a href="/tags" class="btn btn-dark" role="button">Browse All Tags</a>
            </div>
        </div>
    </section>

@endsection