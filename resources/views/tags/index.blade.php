@extends('layouts.app')

@section('content')

<section class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Story Tags</h1>

                <ul>
                @forelse($tags as $tag)
                    <li><a href="{{ $tag->path() }}" class="text-dark">{{ $tag->name }}</a></li>
                @empty
                    <div>There are no tags yet. Create a story or add a tag to one you've got!</div>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection