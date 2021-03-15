@csrf
<div class="form-group">
    <label for="title">Chapter Title</label>
    <input 
        type="text" 
        class="form-control @error('title') is-invalid @enderror" 
        id="title" 
        name="title"
        placeholder="Chapter Title" 
        value="{{ old('title', $chapter->title ?? '') }}"
        required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="body" name="body">Chapter Body</label>
    <textarea 
        class="form-control @error('body') is-invalid @enderror" 
        id="body" 
        name="body"
        rows="15" 
        value="{{ old('body', '') }}"></textarea>
    @error('body')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<button type="submit" class="btn btn-dark mb-2">
    @if($method == 'update')
        Update Chapter
    @else
        Create Chapter
    @endif
</button>