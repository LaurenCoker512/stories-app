@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Create a New Story</h1>
            <form method="POST" action="/stories">

    @include('stories.form', ['story' => new App\Models\Story, 'method' => 'store'])

    <!-- <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Create a New Story</h1>
                <form method="POST" action="/stories">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" placeholder="Story Title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description/Summary</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
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

                    <div class="form-group">
                        <label for="first-chapter">Write your story!</label>
                        <textarea class="form-control" id="first-chapter" rows="15"></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark mb-2">Create Story</button>
                    
                </form>
            </div>
        </div>
    </div> -->

@endsection