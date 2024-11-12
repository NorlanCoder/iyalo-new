@extends('auth.template')

@section('title','Connexion')

@section('body')

  <div class="card-body">

    <div class="brand-wrapper" style="font-size:1.5em">
      @if (Session::get('danger'))
        <div class="alert alert-danger">
          {{ Session::get('danger')}}
        </div>
      @endif
      @if (Session::get('success'))
        <div class="alert alert-success">
          {{ Session::get('success')}}
        </div>
      @endif
    </div>

    <a href="{{ asset('') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
      <img src="{{asset('logo.png')}}" width="180" alt="">
    </a>
    <p class="text-center">Espace de connexion pour tous les utilisateurs</p>
    <form action="{{route('auth.check')}}" method="post">
      @csrf
      <div class="mb-3">
        <label for="exampleInput" class="form-label">Adresse Mail</label>
        <input type="text" class="form-control" id="exampleInput"  name="email">
        <span class="text-danger">@error('matricule'){{ $message }} @enderror </span>
      </div>
      <div class="mb-4">
        <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="exampleInputPassword1"  name="password">
        <span class="text-danger">@error('password'){{ $message }} @enderror </span>
      </div>
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="form-check">
          <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
          <label class="form-check-label text-dark" for="flexCheckChecked">
            Se rappeller de moi
          </label>
        </div>
        <a class="text-primary fw-bold" href="{{route('auth.forget')}}">Mot de passe oublié ?</a>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" style="background-color: #00ddb3; border-color: #00ddb3">Se Connecter</button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-4 mb-0 fw-bold">Vous avez pas de compte?</p>
      </div>
    </form>
  </div>
@endsection
