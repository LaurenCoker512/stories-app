@extends('layouts.app')

@section('content')
    <div class="jumbotron" style="background-image: url('https://miro.medium.com/max/2400/0*5dZpxRIiURrp8obS'); background-repeat: no-repeat; background-position: center; background-size: cover;">
        <h1 class="display-4 text-white text-center">Welcome to Stories!</h1>
        <p class="lead text-white text-center">We're an archive of all sorts of writing, from fiction to nonfiction to poetry.</p>
        <p class="lead text-white text-center">
            <a class="btn btn-outline-light btn-lg" href="#" role="button">Create a Story</a>
        </p>
    </div>

    <section class="container">
        <div class="row">
            <div class="col-9">
                <h2>Latest Stories</h2>

                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>
                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>
                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>
                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>
                <div class="card mt-4" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Story Title</h5>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis turpis eget massa tincidunt aliquet. Nunc mattis quis libero quis laoreet. In hac habitasse platea dictumst. Ut ac arcu eros. Phasellus vel ullamcorper nunc. Aenean convallis ultricies velit sed ultrices. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                        </p>
                        <a href="#" class="btn btn-dark">Read more</a>
                    </div>
                </div>
                <!-- @forelse($stories as $story)
                    <div>Dynamic content here</div>
                @empty
                    <div>There are no stories yet. Create one here!</div>
                @endforelse -->
            </div>
            <div class="col-3">
                <h2>Popular Tags</h2>
                <ul class="list-group mt-4 mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-dark">Cras justo odio</a>
                        <span class="badge badge-dark badge-pill">14</span>
                    </li>
                </ul>
                
                <a href="#" class="btn btn-dark" role="button">Browse All Tags</a>
            </div>
        </div>
    </section>

@endsection