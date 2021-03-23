@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <div class="row">
            @if($story->chapters->count() > 1)
            <div class="col-md-9 col-12">
            @else
            <div class="col-12">
            @endif
                <!-- Subscribe/Unsubscribe -->
                @if(auth()->id() && (auth()->id() !== $story->author->id) && !$userIsSubscribed)
                <form method="POST" action="/subscriptions/story/{{ $story->id }}" class="d-inline-block float-right">
                    @csrf
                    <input 
                        class="btn btn-dark" 
                        type="submit" 
                        value="Subscribe">
                </form>
                @elseif(auth()->id() && (auth()->id() !== $story->author->id) && $userIsSubscribed)
                <form method="POST" action="/subscriptions/story/{{ $story->id }}" class="d-inline-block float-right">
                    @method('DELETE')
                    @csrf
                    <input 
                        class="btn btn-dark" 
                        type="submit" 
                        value="Unsubscribe">
                </form>
                @endif

                <h1>{{ $story->title }}</h1>

                <h2 class="h4"><a href="{{ $story->author->path() }}" class="text-dark">{{ $story->author->name }}</a></h2>

                <p>{{ $story->description }}</p>

                <div class="mb-4">
                @foreach($story->tags as $tag)
                    <a href="{{ $tag->path() }}" class="badge badge-dark">{{ $tag->name }}</a>
                @endforeach
                </div>

                @if(auth()->id() === $story->author->id)
                <div>
                    <a href="{{ $story->path() }}/chapters/create" class="btn btn-dark" role="button">Add Chapter</a>
                    <a href="{{ $story->path() }}/edit" class="btn btn-dark" role="button">Edit Story</a>
                    <a href="{{ $chapter->path() }}/edit" class="btn btn-dark" role="button">Edit Chapter</a>
                    <form method="POST" action="{{ $story->path() }}" class="d-inline-block">
                        @method('DELETE')
                        @csrf
                        <input 
                            class="btn btn-dark" 
                            type="submit" 
                            value="Delete Story" 
                            onclick="return confirm('Are you sure you want to delete this story?');">
                    </form>
                    <form method="POST" action="{{ $chapter->path() }}" class="d-inline-block">
                        @method('DELETE')
                        @csrf
                        <input 
                            class="btn btn-dark" 
                            type="submit" 
                            value="Delete Chapter" 
                            onclick="return confirm('Are you sure you want to delete this chapter?');">
                    </form>
                </div>
                @endif

                <article class="mt-4 mb-4">
                    {!! clean($chapter->body) !!}
                </article>

                <h3>Leave a comment</h3>

                <form 
                    class="mb-4" 
                    method="POST" 
                    action="/stories/{{ $story->id }}/chapters/{{ $chapter->getNumber() }}/comments">
                    @csrf
                    <div class="form-group">
                        <label for="body" class="sr-only">Comment</label>
                        <textarea 
                            class="form-control" 
                            id="body" 
                            name="body"
                            rows="4" 
                            required
                        ></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark">Submit</button>
                </form>

                @if($comments->count() > 0)
                <h3>Comments</h3>
                    @foreach($comments as $comment)
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">{{ $comment->author->name ?? 'Guest' }}</h5>
                                <p>{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</p>
                                <p class="card-text">{{ $comment->body }}</p>
                                @can('update', $comment)
                                <button 
                                    type="button" 
                                    class="btn btn-dark" 
                                    data-toggle="modal" 
                                    data-target="#comment-{{ $comment->id }}"
                                >Edit Comment</button>
                                <form method="POST" action="{{ $comment->path() }}" class="d-inline-block">
                                    @method('DELETE')
                                    @csrf
                                    <input 
                                        class="btn btn-dark" 
                                        type="submit" 
                                        value="Delete Comment" 
                                        onclick="return confirm('Are you sure you want to delete this comment?');">
                                </form>
                                @endcan
                            </div>
                        </div>
                        @can('update', $comment)
                        <!-- Edit comment modal -->
                        <div class="modal fade" id="comment-{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form 
                                        class="mb-4" 
                                        method="POST" 
                                        action="/stories/{{ $story->id }}/chapters/{{ $chapter->getNumber() }}/comments/{{ $comment->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="form-group">
                                                <label for="body" class="sr-only">Comment</label>
                                                <textarea 
                                                    class="form-control" 
                                                    id="body" 
                                                    name="body"
                                                    rows="4" 
                                                    required
                                                >{{ $comment->body }}</textarea>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-dark">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endcan
                    @endforeach
                @endif
            </div>
            @if($story->chapters->count() > 1)
            <div class="col-md-3 col-12">
                <h2>Chapters</h2>
                <ul class="list-group mt-4 mb-4">
                    @foreach($story->chapters as $chap)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ $chap->path() }}" class="text-dark">
                            @if($chap->name)
                                {{ $chap->name }}
                            @else
                                Chapter {{ $chap->getNumber() }}
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    
@endsection