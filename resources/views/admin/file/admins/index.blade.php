@extends('admin.template')

@section('title','Administrateurs')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Administrateurs
                    </h5>  
                    <div class="">
                    <a class="btn btn-outline-primary" type="button"  data-bs-toggle="modal" data-bs-target="#add">Ajouter un Administrateur</a>
                    </div>
                </div> 
                <div class="mx-5 px-5 my-4" >
                    <form method="get" class="row text-center">
                        <div class="col-sm-8">
                            <input type="text" name="q" value="{{ isset($q)? $q : '' }}" class="form-control" placeholder="Rechercher.....">
                        </div>
                        <div class="col-sm-4">
                            <input type="submit" value="Rechercher" class="btn btn-primary">
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($user->image_url ?: 'uploads/profil/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $user->name }} </h6>
                                        </div>
                                    </td>
                                    <td> {{ $user->email }} </td>
                                    <td>
                                        {{ $user->phone }}
                                    </td>
                                    <td>
                                        @if($user->status)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        @if($user->status)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$user->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$user->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.admins.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $users->links() }}
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Add Admin -->
    <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white" id="staticBackdropLabel" >Ajouter un Administrateur</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{route('admin.admins.create')}}" method="post" >
                    @csrf
                    <div class="modal-body">
                        <h5>Informations</h5>
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label for="">Nom Complet<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                <span class="text-danger">@error('name'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Email <span class="text-danger">*</span> </label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                <span class="text-danger">@error('email'){{ $message }} @enderror </span>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label for="">Télephone <span class="text-danger">*</span> </label>
                                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" required>
                                <span class="text-danger">@error('phone'){{ $message }} @enderror </span>
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
                "order": [[ 4, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection