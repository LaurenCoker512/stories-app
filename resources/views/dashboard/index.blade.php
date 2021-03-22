@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        @if(auth()->id() === $user->id)
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
        @else
            <!-- Subscribe/Unsubscribe -->
            @if(auth()->id() && !$userIsSubscribed)
            <form method="POST" action="/subscriptions/user/{{ $user->id }}" class="d-inline-block float-right">
                @csrf
                <input 
                    class="btn btn-dark" 
                    type="submit" 
                    value="Subscribe">
            </form>
            @elseif(auth()->id() && $userIsSubscribed)
            <form method="POST" action="/subscriptions/user/{{ $user->id }}" class="d-inline-block float-right">
                @method('DELETE')
                @csrf
                <input 
                    class="btn btn-dark" 
                    type="submit" 
                    value="Unsubscribe">
            </form>
            @endif
            <h1>{{ $user->name }}'s Stories</h1>
        @endif
        <div class="row">
            @if(auth()->id() === $user->id)
            <div class="col-md-9 col-12">
                
                <h2>Your Stories</h2>

                <a href="/stories/create" class="btn btn-dark" role="button">Create a New Story</a>
            @else
            <div class="col-12">
            @endif

                @forelse($stories as $story)
                    <x-story :story="$story" :user="$story->user"/>
                @empty
                    @if(auth()->id() === $user->id)
                        <div class="mt-4">You haven't posted any stories yet.</div>
                    @else
                        <div class="mt-4">This user hasn't posted any stories yet.</div>
                    @endif
                @endforelse

                <br/>

                {{ $stories->links() }}

                <br/>
            </div>
            @if(auth()->id() === $user->id)
            <div class="col-md-3 col-12">
                <h2>Your Subscriptions</h2>

                @forelse($authorSubs as $story)
                    <x-story :story="$story" :user="$story->user"/>
                @empty
                    <div class="mt-4">You haven't subscribed to any stories yet.</div>
                @endforelse

                @if(count($authorSubs) > 5)
                    <a href="/subscriptions" class="btn btn-dark" role="button">View All Subscriptions</a>
                @endif

            </div>
            @endif
        </div>
    </section>

@endsection