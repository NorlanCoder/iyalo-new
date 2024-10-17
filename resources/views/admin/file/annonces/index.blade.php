@extends('admin.template')

@section('title','Annonces')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Annonces
                    </h5>  
                    <div class="">
                    <a class="btn btn-outline-primary" type="button"  data-bs-toggle="modal" data-bs-target="#add">Ajouter une Annonce</a>
                    </div>
                </div>                
                <div class="mt-4">
                    <form method="get" class="row text-right">
                        <div class="col-sm-7">
                            <input type="text" name="q" value="{{ isset($q)? $q : '' }}" class="form-control" placeholder="Rechercher.....">
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" value="Rechercher" class="btn btn-primary">
                        </div>
                    </form>
                </div> 
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Informations</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Informations</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($annonces as $annonce)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($annonce->image)}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $annonce->title }} </h6>
                                        </div>
                                    </td>
                                    <td>
                                        {{$annonce->type}} <br> {{$annonce->adresse}}
                                    </td>
                                    <td>
                                        @if($annonce->active)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $annonce->created_at }}</td>
                                    <td>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#update{{$annonce->id}}" class="btn bg-warning-subtle"><i class="ti ti-edit"></i></a>
                                        @if($annonce->active)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$annonce->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$annonce->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.annonces.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $annonces->links() }}
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Add Admin -->
    <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white text-white" id="staticBackdropLabel" >Ajouter une Annonce</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.annonces.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="">Titre<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                <span class="text-danger">@error('title'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type d'annonces<span class="text-danger">*</span> </label>
                                <select name="type" id="" class="form-control" required>
                                    <option value="">Veuillez choisir</option>
                                    <option value="Agence de demenagement">Agence de demenagement</option>
                                    <option value="Agence de menage">Agence de menage</option>
                                </select>
                                <span class="text-danger">@error('type'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Adresse<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="adresse" value="{{ old('adresse') }}" required>
                                <span class="text-danger">@error('adresse'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Image<span class="text-danger">*</span> </label>
                                <input type="file" class="form-control" name="image" value="{{ old('image') }}" required>
                                <span class="text-danger">@error('image'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Description<span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="description" id="">{{ old('description') }}</textarea>
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
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                },
                "order": [[ 3, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection