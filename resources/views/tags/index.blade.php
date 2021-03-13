@extends('layouts.app')

@section('content')

<section class="container">
        <div class="row">
            <div class="col-12">
                <h2>Story Tags</h2>

                <li><a href="{{ $tag->path() }}">{{ $tag->name }}</a></li>

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>

                <ul>
                @forelse($tags as $tag)
                    <li><a href="{{ $tag->path() }}">{{ $tag->name }}</a></li>
                @empty
                    <div>There are no stories yet. Create one here!</div>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection