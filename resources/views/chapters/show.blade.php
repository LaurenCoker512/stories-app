@extends('layouts.app')

@section('content')
    <h1>{{ $story->title }}</h1>

    <p>{{ $story->description }}</p>

    <ul>
        @foreach($story->tags as $tag)
            <li>{{ $tag->name }}</li>
        @endforeach
    </ul>

    {{ $chapter->body }}

    <a href="/stories">Back to Stories</a>
@endsection