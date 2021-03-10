@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Update {{ $story->title }}</h1>
            <form method="POST" action="{{ $story->path() }}">
            @method('PATCH')

    @include('stories.form', ['method' => 'update'])

@endsection