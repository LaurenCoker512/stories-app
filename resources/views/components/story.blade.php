<div class="card mt-4" style="width: 100%;">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ $story->firstChapterPath() }}" class="text-dark">{{ $story->title }}</a>
        </h5>
        <p class="card-text font-weight-bold">
            <a href="{{ $story->user->path() }}" class="text-dark">{{ $user->name }}</a>
        </p>
        <p class="card-text">
            {{ $story->description }}
        </p>
        @if(auth()->id() === $user->id)
        <a href="{{ $story->path() }}/edit" class="btn btn-dark">Edit Story</a>
        <form method="POST" action="{{ $story->path() }}" class="d-inline-block">
            @method('DELETE')
            @csrf
            <input 
                class="btn btn-dark" 
                type="submit" 
                value="Delete Story" 
                onclick="return confirm('Are you sure you want to delete this story?');">
        </form>
        @endif
    </div>
</div>