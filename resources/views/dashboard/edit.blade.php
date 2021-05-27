@extends('layouts.app')

@section('content')

    <section class="container mt-4">
        <h1>Edit Your User Info</h1>
        <div class="row">
            <div class="col-md-6 col-12">
              <form method="POST" action="/dashboard/{{ $user->id }}">
                  @csrf
                  @method('PATCH')

                  <div class="form-group row">
                      <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                      <div class="col-md-6">
                          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $user->name }}" required autocomplete="name" autofocus>

                          @error('name')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                      <div class="col-md-6">
                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $user->email }}" required autocomplete="email">

                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="password" class="col-md-4 col-form-label text-md-right">Old Password</label>

                      <div class="col-md-6">
                          <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" autocomplete="old-password">

                          @error('old_password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="new_password" class="col-md-4 col-form-label text-md-right">New Password</label>

                      <div class="col-md-6">
                          <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" autocomplete="new-password">

                          @error('new_password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>

                      <div class="col-md-6">
                          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                      <div class="col-md-6">
                          <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') ?? $user->phone }}" autocomplete="phone">

                          @error('phone')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="website" class="col-md-4 col-form-label text-md-right">Website (Personal, Social Media, etc.)</label>

                      <div class="col-md-6">
                          <input id="website" type="text" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website') ?? $user->website }}" autocomplete="website">

                          @error('website')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>

                  <div class="form-group row mb-0">
                      <div class="col-md-6 offset-md-4">
                          <button type="submit" class="btn btn-dark">
                              Update Info
                          </button>
                      </div>
                  </div>
              </form>
            </div>
            <div class="col-md-3 col-12">
                <div 
                    class="img-circular mb-4 ml-auto mr-auto" 
                    style="background-image: url('{{ $user->getUserAvatar() }}');">
                </div>
                
                <button 
                    type="button" 
                    class="btn btn-dark d-block ml-auto mr-auto" 
                    data-toggle="modal" 
                    data-target="#avatar-upload"
                >
                    @if($user->avatar)
                    Change Avatar
                    @else
                    Upload an Avatar
                    @endif
                </button>

            </div>
        </div>
    </section>

    <div class="modal fade" id="avatar-upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form 
                    class="mb-4" 
                    method="POST" 
                    action="/dashboard/{{ $user->id }}/image"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create an Avatar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label for="image">Upload a new image</label>
                            <input id="image" type="file" class="form-control-file" name="image">
                        </div>

                        <div class="form-group">
                            <label for="url">Link to an existing image</label>
                            <input id="url" type="text" class="form-control" name="url">
                        </div>

                        <small>Note: If you upload an image, that will take precedence over a link.</small>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection