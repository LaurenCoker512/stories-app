@extends('layouts.app')

@section('content')

<section class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Stories with {{ $tag->name }} Tag</h1>

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

                <br/>

                {{ $stories->links() }}

                <br/>
            </div>
        </div>
    </div>
</section>

@endsection