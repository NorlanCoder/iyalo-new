@extends('admin.template')

@section('title','Culture Agricol')
@section('users','active')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        <a class="text-primary" href="{{ route('admin.users.cultures',$culture->site->id) }}"> <i class='ti ti-arrow-left'></i></a>
                        Historiques des Vannes sur la Culture {{ $culture->name }} de {{ $culture->site->user->firstname }}
                    </h5>  
                    <div class="">
                    <a class="btn btn-outline-primary" type="button"  data-bs-toggle="modal" data-bs-target="#add">Ajouter un Vanne</a>
                    </div>
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Adresse MAC</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Code</th>
                                <th>Adresse MAC</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($vannes as $vanne)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <h6 class="mb-0 text-center"> {{ $vanne->code }}
                                        </div>
                                    </td>
                                    <td> {{ $vanne->adress_mac }}</td>
                                    <td>
                                        {{ $vanne->infos }}
                                    </td>
                                    <td>
                                        @if($vanne->is_active)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $vanne->created_at }}</td>
                                    <td>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#edit{{$vanne->id}}" class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                        @if($vanne->is_active)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$vanne->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$vanne->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.users.vanne_modal')
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
                    <h3 class="modal-title text-white" id="staticBackdropLabel" >Ajouter une Vanne</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.users.create_vanne',$culture->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="">Code <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                                <span class="text-danger">@error('code'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Adresse MAC <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="adress_mac" value="{{ old('adress_mac') }}" required>
                                <span class="text-danger">@error('adress_mac'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="">Description <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="infos">{{ old('infos') }}</textarea>
                                <span class="text-danger">@error('infos'){{ $message }} @enderror </span>
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