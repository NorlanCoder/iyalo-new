<!-- Action -->
<div class="modal fade" id="action{{$annonce->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $annonce->active ? 'danger' : 'success'}}">
                <h3 class="modal-title text-white" id="staticBackdropLabel" >Voulez-vous {{ $annonce->active ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.annonces.action',$annonce->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $annonce->active ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Admin -->
<div class="modal fade" id="update{{$annonce->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title text-white text-white" id="staticBackdropLabel" >Modifier une Annonce</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{route('admin.annonces.update', $annonce->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                
                <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="">Titre<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="title" value="{{ $annonce->title }}" required>
                                <span class="text-danger">@error('title'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type d'annonces<span class="text-danger">*</span> </label>
                                <select name="type" id="" class="form-control" required>
                                    <option value="">Veuillez choisir</option>
                                    <option value="Agence de demenagement" {{ $annonce->type == "Agence de demenagement" ? 'selected' : '' }} >Agence de demenagement</option>
                                    <option value="Agence de menage" {{ $annonce->type == "Agence de menage" ? 'selected' : '' }}>Agence de menage</option>
                                </select>
                                <span class="text-danger">@error('type'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Adresse<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="adresse" value="{{ $annonce->adresse }}" required>
                                <span class="text-danger">@error('adresse'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Image<span class="text-warning">facultatif</span> </label>
                                <input type="file" class="form-control" name="image" value="{{ old('image') }}">
                                <span class="text-danger">@error('image'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Description<span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="description" id="">{{ $annonce->description }}</textarea>
                                <span class="text-danger">@error('description'){{ $message }} @enderror </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Valider</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>