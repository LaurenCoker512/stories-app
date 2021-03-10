
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="title">Title</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="title" 
                        placeholder="Story Title" 
                        value="{{ $story->title }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="description">Description/Summary</label>
                    <textarea 
                        class="form-control" 
                        id="description" 
                        rows="3" 
                        value="{{ $story->description }}"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select multiple class="form-control" id="tags">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    </select>
                </div>

                @if($method == 'store')
                    <div class="form-group">
                        <label for="first-chapter">Write your story!</label>
                        <textarea class="form-control" id="first-chapter" rows="15"></textarea>
                    </div>
                @endif

                <button type="submit" class="btn btn-dark mb-2">
                    @if($method == 'update')
                        Update Story
                    @else
                        Create Story
                    @endif
                </button>

                @if ($errors->any())
                <div class="mt-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
                @endif
                
            </form>
        </div>
    </div>
</div>