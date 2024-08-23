@extends('auth.template')

@section('title','Réinitialisation moot de passe')

@section('body')

  <div class="card-body">
    <a href="{{ asset('') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
      <img src="{{asset('admin/images/logos/dark-logo.svg')}}" width="180" alt="">
    </a>
    <p class="text-center">Changer le mot de passe : <span class="text-primary">{{$user->email}}</sapn></p>
    <form action="{{route('auth.update_pass',$user->id)}}" method="post">
        @csrf
        @method('put')
        <div class="mb-4">
          <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="exampleInputPassword1"  name="password">
          <span class="text-danger">@error('password'){{ $message }} @enderror </span>
        </div>
        <div class="mb-4">
          <label for="exampleInputPassword2" class="form-label">Confirmé mot de passe</label>
          <input type="password" class="form-control" id="exampleInputPassword2"  name="confirm">
          <span class="text-danger">@error('confirm'){{ $message }} @enderror </span>
        </div>
      <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Changer le mot de passe</button>
    </form>
  </div>
  
@endsection