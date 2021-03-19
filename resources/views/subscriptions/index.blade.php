@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        <h1>Your Subscriptions</h1>
        <div class="row">

            <div class="col-12">
                @forelse($subscriptions as $story)
                    <x-story :story="$story" :user="$story->user"/>
                @empty
                    @if(auth()->id() === $user->id)
                        <div class="mt-4">You haven't posted any stories yet.</div>
                    @else
                        <div class="mt-4">This user hasn't posted any stories yet.</div>
                    @endif
                @endforelse
            </div>
        </div>
    </section>

@endsection