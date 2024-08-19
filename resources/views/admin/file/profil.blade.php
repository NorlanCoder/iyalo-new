@extends('admin.template')

@section('title','Mon Profil')

@section('body')
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Informations Personnelles</h5>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{route('admin.update_info',auth()->user()->id)}}">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="firstname" class="form-label">Nom</label>
                                <input type="text" class="form-control" name="firstname" value="{{ auth()->user()->firstname }}">
                                <div id="emailHelp" class="form-text text-danger">@error('firstname'){{ $message }} @enderror</div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="lastname" class="form-label">Prénoms</label>
                                <input type="text" class="form-control" name="lastname" value="{{ auth()->user()->lastname }}">
                                <div id="emailHelp" class="form-text text-danger">@error('lastname'){{ $message }} @enderror</div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="email" class="form-label">Email Electronique</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" value="{{ auth()->user()->email }}" placeholder="Email address">
                                <div id="emailHelp" class="form-text text-danger">@error('email'){{ $message }} @enderror</div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="phonenumber" class="form-label">Télephone</label>
                                <input type="tel" class="form-control" name="phonenumber" value="{{ auth()->user()->phonenumber }}">
                                <div id="emailHelp" class="form-text text-danger">@error('phonenumber'){{ $message }} @enderror</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Changer les Informations</button>
                    </form>
                </div>
            </div>
            <h5 class="card-title fw-semibold mb-4">Mot de passe</h5>
            <div class="card mb-0">
                <div class="card-body">
                    <form method="post" action="{{route('admin.update_pass',auth()->user()->id)}}">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="older" class="form-label">Ancien Mot de passe</label>
                            <input type="password" name="older" class="form-control" id="exampleInputPassword1">
                            <div id="emailHelp" class="form-text text-danger">@error('older'){{ $message }} @enderror</div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                                <div id="emailHelp" class="form-text text-danger">@error('password'){{ $message }} @enderror</div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="confirm" class="form-label">Confirmer mot de passe</label>
                                <input type="password" name="confirm" class="form-control" id="exampleInputPassword1">
                                <div id="emailHelp" class="form-text text-danger">@error('confirm'){{ $message }} @enderror</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection