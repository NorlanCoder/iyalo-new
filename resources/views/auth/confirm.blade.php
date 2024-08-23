@extends('auth.template')

@section('title','Activation du compte')

@section('body')
  <div class="card-body">    
    <a href="{{ asset('') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
      <img src="{{asset('admin/images/logos/dark-logo.svg')}}" width="180" alt="">
    </a>
    <p class="text-center">Lien d'activation du compte</p>
    Un lien d'activation du compte a été envoyé sur votre mail. 
    <form action="{{route('auth.resend_email')}}" method="post">
      @csrf 
      <input type="hidden" name="email" value="{{ auth()->user()->email}}">
      Veuillez <button type="submit" class="btn btn-link">cliquer ici</button>pour renvoyer.
    </form> 
    <p class="login-card-footer-text text-center"><a class="btn btn-danger" href="{{route('auth.logout')}}" class="text-reset">Déconnexion </a></p>

  </div>
@endsection