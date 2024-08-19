
<div class="modal fade" id="action{{$culture->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $culture->is_active ? 'danger' : 'success'}}">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{ $culture->is_active ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.users.action_culture',$culture->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $culture->is_active ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin -->
<div class="modal fade" id="edit{{$culture->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h3 class="modal-title text-white" id="staticBackdropLabel" >Modifier {{$culture->name}}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.users.edit_culture',$culture->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="">Nom <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name" value="{{ $culture->name }}" required>
                                <span class="text-danger">@error('name'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type de Culture <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="type_culture" value="{{ $culture->type_culture }}" required>
                                <span class="text-danger">@error('type_culture'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Semence <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="semence" value="{{ $culture->semence }}">
                                <span class="text-danger">@error('semence'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type Irragation <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="irrigation_type" value="{{ $culture->irrigation_type }}">
                                <span class="text-danger">@error('irrigation_type'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Superficie <span class="text-danger">*</span> </label>
                                <input type="number" step="0.01" min="0" class="form-control" name="superficie" value="{{ $culture->superficie }}">
                                <span class="text-danger">@error('superficie'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Image <span class="text-warning">Facultatif</span> </label>
                                <input type="file" step="0.01" min="0" class="form-control" name="image" value="{{ $culture->image }}">
                                <span class="text-danger">@error('image'){{ $message }} @enderror </span>
                            </div>
                            
                            <div class="col-sm-12 mb-3">
                                <label for="">Description <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="description">{{ $culture->description }}</textarea>
                                <span class="text-danger">@error('description'){{ $message }} @enderror </span>
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