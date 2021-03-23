@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Update {{ $chapter->story->title }} - {{ $chapter->name ?? 'Chapter ' . $chapter->getNumber() }}</h1>
                {{-- <form method="POST" action="{{ $chapter->story->path() }}" class="mb-4">
                    @method('PATCH')
                
                    @include('chapters.form', ['method' => 'update'])
                </form> --}}

                <chapter-form
                    method="update"
                    story-id="{{ $chapter->story->id }}"
                    chapter-id="{{ $chapter->getNumber() }}"
                    name="{{ $chapter->name ?? '' }}"
                    body="{{ $chapter->body }}"
                ></chapter-form>

                <a href="{{ $chapter->story->path() }}/edit" class="btn btn-dark">Back to Story</a>
            </div>
        </div>
    </div>
    
@endsection