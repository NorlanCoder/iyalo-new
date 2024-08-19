
<div class="modal fade" id="action{{ $vanne->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{  $vanne->is_active ? 'danger' : 'success'}}">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{  $vanne->is_active ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.users.action_vanne', $vanne->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{  $vanne->is_active ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin -->
<div class="modal fade" id="edit{{$vanne->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h3 class="modal-title text-white" id="staticBackdropLabel" >Modifier {{ $vanne->code}}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.users.edit_vanne',$vanne->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="">Code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="code" value="{{ $vanne->code }}" required>
                                <span class="text-danger">@error('code'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Adresse MAC <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="adress_mac" value="{{ $vanne->adress_mac }}" required>
                                <span class="text-danger">@error('adress_mac'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Description <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="infos">{{ $vanne->infos }}</textarea>
                                <span class="text-danger">@error('infos'){{ $message }} @enderror </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Modifier</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>