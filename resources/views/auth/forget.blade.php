@extends('auth.template')

@section('title','Mot de passe oublié')

@section('body')
  <div class="card-body">
    <div class="brand-wrapper" style="font-size:1.5em">
      @if (Session::get('danger'))
        <div class="alert alert-danger">
          {{ Session::get('danger')}}
        </div>
      @endif
    </div>
    
    <a href="{{ asset('') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
      <img src="{{asset('admin/images/logos/dark-logo.svg')}}" width="180" alt="">
    </a>
    <p class="text-center">Récupération du mot de passe</p>
    <form action="{{route('auth.exist')}}" method="post">
      @csrf
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email</label>
        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
        <span class="text-danger">@error('matricule'){{ $message }} @enderror </span>
      </div>

      <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Valider</button>

      <div class="d-flex align-items-center justify-content-center">
        <a class="text-primary fw-bold ms-2" href="{{route('auth.login')}}">Retour</a>
      </div>
    </form>

  </div>
@endsection