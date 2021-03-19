@extends('layouts.app')

@section('content')
    <section class="container mt-4 mb-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ ucfirst($type) }} Stories</h1>

                @forelse($stories as $story)
                    <x-story :story="$story" :user="$story->user"/>
                @empty
                    <div>There are no stories in this category yet.</div>
                @endforelse

                <br/>

                {{ $stories->links() }}

                <br/>

            </div>
        </div>
    </section>

@endsection