<!-- Action -->
<div class="modal fade" id="action{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $user->status ? 'danger' : 'success'}}">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{ $user->status ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.clients.action',$user->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $user->status ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="become{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title" id="staticBackdropLabel" >Devenir un Annonceur </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                Adresse : {{$user->adress}} <br>
                <div class="row text-center">
                    <div class="col-sm-6">
                        <a href="{{ asset($user->logo) }}" class="btn bg-warning-subtle btn-lg" data-fancybox="gallery{{$user->id}}" data-caption="Logo">
                            Logo
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ asset($user->card_image) }}" class="btn bg-primary-subtle btn-lg" data-fancybox="gallery{{$user->id}}" data-caption="Carte">
                            Carte
                        </a> 
                    </div>
                </div>
                <br>
                <form action="{{ route('admin.clients.become',$user->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
