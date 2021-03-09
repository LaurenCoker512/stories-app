@extends('layouts.app')

@section('content')
    <h1>{{ $story->title }}</h1>

    <p>{{ $story->description }}</p>

    @foreach ($story->chapters as $chapter)
        {{ $chapter->body }}
    @endforeach

    <a href="/stories">Back to Stories</a>
@endsection