@extends('admin.template')

@section('title','Culture Agricol')
@section('users','active')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        <a class="text-primary" href="{{ route('admin.users.sites',$site->user->id) }}"> <i class='ti ti-arrow-left'></i></a>
                        Historiques des Culture sur le Site de {{ $site->name }} de {{ $site->user->firstname }}
                    </h5>  
                    <div class="">
                    <a class="btn btn-outline-primary" type="button"  data-bs-toggle="modal" data-bs-target="#add">Ajouter une Culture</a>
                    </div>
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Informations</th>
                                <th>Adresse</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Informations</th>
                                <th>Adresse</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($cultures as $culture)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($culture->image ?: 'site/images.jpeg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $culture->name }} <br> {{ $culture->type_culture }} <br> 
                                        {{ $culture->superficie }}m²</h6>
                                        </div>
                                    </td>
                                    <td> {{ $culture->semence }} <br> {{ $culture->irrigation_type }}</td>
                                    <td>
                                        {{ $culture->description }}
                                    </td>
                                    <td>
                                        @if($culture->is_active)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $culture->created_at }}</td>
                                    <td>
                                        <a href="{{route('admin.users.vannes', $culture->id)}}" class="btn btn-primary" title="Mes Vannes"><i class="ti ti-eye"></i></a>
                                        
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#edit{{$culture->id}}" class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                        @if($culture->is_active)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$culture->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$culture->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.users.culture_modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Add Admin -->
    <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white" id="staticBackdropLabel" >Ajouter une Culture</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.users.create_culture',$site->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label for="">Nom <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                <span class="text-danger">@error('name'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type de Culture <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="type_culture" value="{{ old('type_culture') }}" required>
                                <span class="text-danger">@error('type_culture'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Semence <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="semence" value="{{ old('semence') }}">
                                <span class="text-danger">@error('semence'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Type Irragation <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="irrigation_type" value="{{ old('irrigation_type') }}">
                                <span class="text-danger">@error('irrigation_type'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Superficie <span class="text-danger">*</span> </label>
                                <input type="number" step="0.01" min="0" class="form-control" name="superficie" value="{{ old('superficie') }}">
                                <span class="text-danger">@error('superficie'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Image <span class="text-danger">*</span> </label>
                                <input type="file" step="0.01" min="0" class="form-control" name="image" value="{{ old('image') }}">
                                <span class="text-danger">@error('image'){{ $message }} @enderror </span>
                            </div>
                            
                            <div class="col-sm-12 mb-3">
                                <label for="">Description <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                                <span class="text-danger">@error('description'){{ $message }} @enderror </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
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
                // "order": [[ 5, 'desc' ]],
            });
            
        });
    </script>
@endsection