@extends('layouts.app')

@section('content')

    <section class="container mb-4">
        <div class="row">
            <div class="col-12">
                <h1>Search Results</h1>

                @forelse($stories as $story)
                    <div class="card mt-4" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text font-weight-bold">
                                <a href="{{ $story->user->path() }}" class="text-dark">{{ $story->user->name }}</a>
                            </p>
                            <p class="card-text">
                                {{ $story->description }}
                            </p>
                            <a href="{{ $story->firstChapterPath() }}" class="btn btn-dark">Read more</a>
                        </div>
                    </div>
                @empty
                    <div>There are no results for that query.</div>
                @endforelse
            </div>
        </div>
    </section>

@endsection