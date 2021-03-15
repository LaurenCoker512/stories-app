@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Update {{ $story->title }}</h1>
            <form method="POST" action="{{ $story->path() }}" class="mb-4">
            @method('PATCH')

            @include('stories.form', ['method' => 'update'])

            </form>

            <h2>Edit Chapters</h2>
            <ul>
                @foreach($story->chapters as $chapter)
                    <li><a href="{{ $chapter->path() }}/edit">
                        @if($chapter->name)
                            {{ $chapter->name }}
                        @else
                            Chapter {{ $chapter->getNumber() }}
                        @endif
                    </a></li>
                    
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection