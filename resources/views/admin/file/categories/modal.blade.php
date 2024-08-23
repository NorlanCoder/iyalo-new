<!-- Action -->
<div class="modal fade" id="action{{$category->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $category->status ? 'danger' : 'success'}}">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{ $category->status ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.categories.action',$category->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $category->status ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Admin -->
<div class="modal fade" id="update{{$category->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title text-white" id="staticBackdropLabel" >Modifier une Categorie</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{route('admin.categories.update', $category->id)}}" method="post" >
                @csrf
                @method('put')
                <div class="modal-body">
                    <h5>Informations</h5>
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <label for="">Catégorie<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="label" value="{{ $category->label }}" required>
                            <span class="text-danger">@error('name'){{ $message }} @enderror </span>
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