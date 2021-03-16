@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <div class="row">
            @if($story->chapters->count() > 1)
            <div class="col-md-9 col-12">
            @else
            <div class="col-12">
            @endif
                <h1>{{ $story->title }}</h1>

                <h4><a href="{{ $story->user->path() }}" class="text-dark">{{ $story->user->name }}</a></h4>

                <p>{{ $story->description }}</p>

                <div>
                @foreach($story->tags as $tag)
                    <a href="{{ $tag->path() }}" class="badge badge-dark">{{ $tag->name }}</a>
                @endforeach
                </div>

                @if(auth()->id() === $story->user->id)
                <div>
                    <a href="{{ $story->path() }}/chapters/create" class="btn btn-dark" role="button">Add Chapter</a>
                    <a href="{{ $story->path() }}/edit" class="btn btn-dark" role="button">Edit Story</a>
                    <a href="{{ $chapter->path() }}/edit" class="btn btn-dark" role="button">Edit Chapter</a>
                    <form method="POST" action="{{ $story->path() }}" class="d-inline-block">
                        @method('DELETE')
                        @csrf
                        <input 
                            class="btn btn-dark" 
                            type="submit" 
                            value="Delete Story" 
                            onclick="return confirm('Are you sure you want to delete this story?');">
                    </form>
                    <form method="POST" action="{{ $chapter->path() }}" class="d-inline-block">
                        @method('DELETE')
                        @csrf
                        <input 
                            class="btn btn-dark" 
                            type="submit" 
                            value="Delete Chapter" 
                            onclick="return confirm('Are you sure you want to delete this chapter?');">
                    </form>
                </div>
                @endif

                <article class="mt-4 mb-4">
                    {{ $chapter->body }}
                </article>

                <a href="/stories" class="text-dark">Back to Stories</a>
            </div>
            @if($story->chapters->count() > 1)
            <div class="col-md-3 col-12">
                <h2>Chapters</h2>
                <ul class="list-group mt-4 mb-4">
                    @foreach($story->chapters as $chap)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ $chap->path() }}" class="text-dark">
                            @if($chap->name)
                                {{ $chap->name }}
                            @else
                                Chapter {{ $chap->getNumber() }}
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    
@endsection