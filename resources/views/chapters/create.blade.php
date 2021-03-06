@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Add a New Chapter to {{ $story->title }}</h1>
                {{-- <form method="POST" action="{{ $story->path() }}/chapters" class="mb-4">
                
                    @include('chapters.form', ['method' => 'store'])
                </form> --}}

                <chapter-form
                    method="store"
                    story-id="{{ $story->id }}"
                ></chapter-form>

                <a href="{{ $story->path() }}/edit" class="link-dark text-dark">Back to Story</a>
            </div>
        </div>
    </div>
    
@endsection