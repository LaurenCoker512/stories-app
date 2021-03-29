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
                    <x-story :story="$story" :user="$story->author"/>
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
                <div 
                    class="img-circular mb-4 ml-auto mr-auto" 
                    style="background-image: url('{{ $user->getUserAvatar() }}');">
                </div>
                
                <button 
                    type="button" 
                    class="btn btn-dark d-block ml-auto mr-auto" 
                    data-toggle="modal" 
                    data-target="#avatar-upload"
                >
                    @if($user->avatar)
                    Change Avatar
                    @else
                    Upload an Avatar
                    @endif
                </button>

                <br/>

                <h2>Your Subscriptions</h2>

                @forelse($authorSubs as $story)
                    <x-story :story="$story" :user="$story->author"/>
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

    <div class="modal fade" id="avatar-upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form 
                    class="mb-4" 
                    method="POST" 
                    action="/dashboard/{{ $user->id }}/image"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create an Avatar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label for="image">Upload a new image</label>
                            <input id="image" type="file" class="form-control-file" name="image">
                        </div>

                        <div class="form-group">
                            <label for="url">Link to an existing image</label>
                            <input id="url" type="text" class="form-control" name="url">
                        </div>

                        <small>Note: If you upload an image, that will take precedence over a link.</small>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection