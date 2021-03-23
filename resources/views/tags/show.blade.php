@extends('layouts.app')

@section('content')

<section class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Stories with {{ $tag->name }} Tag</h1>

                @forelse($stories as $story)
                    <x-story :story="$story" :user="$story->author"/>
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