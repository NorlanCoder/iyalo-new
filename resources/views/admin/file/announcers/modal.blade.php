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
                <form action="{{ route('admin.admins.action',$user->id) }}" method="post" >
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

<!-- Action -->
<div class="modal fade" id="free{{$user->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title" id="staticBackdropLabel" >Modifier les frais IYALO</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Veuillez définir le pourcentage que vous prélèverez sur {{ $user->name }} </h5>
                <br>
                <form action="{{ route('admin.announcers.percent',$user->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="mb-4">
                        <label for="percent" class="form-label">Frais</label>
                        <input type="number" class="form-control" step="0.01" min="0" max="100" id="percent" name="percent" value="{{ $user->free }}">
                        <span class="text-danger">@error('percent'){{ $message }} @enderror </span>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
