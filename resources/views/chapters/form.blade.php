@csrf
<div class="form-group">
    <label for="title">Chapter Title</label>
    <input 
        type="text" 
        class="form-control" 
        id="title" 
        name="title"
        placeholder="Chapter Title" 
        value="{{ old('title', $chapter->title ?? '') }}"
        required>
</div>
<div class="form-group">
    <label for="body" name="body">Chapter Body</label>
    <textarea 
        class="form-control" 
        id="body" 
        name="body"
        rows="15" 
        value="{{ old('body', '') }}"></textarea>
</div>
<button type="submit" class="btn btn-dark mb-2">
    @if($method == 'update')
        Update Chapter
    @else
        Create Chapter
    @endif
</button>

@if ($errors->any())
<div class="mt-6">
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</div>
@endif