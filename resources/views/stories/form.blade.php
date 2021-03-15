
@csrf
<div class="form-group">
    <label for="title">Title</label>
    <input 
        type="text" 
        class="form-control @error('title') is-invalid @enderror" 
        id="title" 
        name="title"
        placeholder="Story Title" 
        value="{{ old('title', $story->title ?? '') }}"
        required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="description">Description/Summary</label>
    <textarea 
        class="form-control @error('description') is-invalid @enderror" 
        id="description" 
        name="description"
        rows="3" 
        required>{{ old('description', $story->description ?? '') }}
    </textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="tags">Tags</label>
    <select multiple class="form-control" id="tags" name="tags">
    @foreach($tags as $tag)
    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
    @endforeach
    </select>
</div>

@if($method == 'store')
    <div class="form-group">
        <label for="first-chapter" name="first-chapter">Write your story!</label>
        <textarea 
            class="form-control @error('first-chapter') is-invalid @enderror" 
            id="first-chapter" 
            name="first-chapter"
            rows="15" 
            value="{{ old('first-chapter', '') }}"></textarea>
        @error('first-chapter')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

<button type="submit" class="btn btn-dark mb-2">
    @if($method == 'update')
        Update Story
    @else
        Create Story
    @endif
</button>
                
