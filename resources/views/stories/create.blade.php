@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Create a New Story</h1>
            <form method="POST" action="/stories">

            @include('stories.form', ['story' => new App\Models\Story, 'method' => 'store'])

            </form>
        </div>
    </div>
</div>

@endsection