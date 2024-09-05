@extends('admin.template')

@section('title','Annonceurs')
@section('announcer','active')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Annonceurs
                    </h5>  
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
                                <th>Frais IYALO</th>
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
                                <th>Frais IYALO</th>
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
                                        {{ $user->free }}%
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
                                        <a href="{{route('admin.announcers.properties', $user->id)}}" title="Historiques Propriétés"  class="btn  bg-primary-subtle"><i class="ti ti-home"></i></a>
                                        <a href="{{route('admin.announcers.visits', $user->id)}}" title="Historiques visiteurs" class="btn  bg-warning-subtle"><i class="ti ti-users"></i></a>
                                        <a href="{{route('admin.announcers.wallets', $user->id)}}" title="Historiques transactions" class="btn  bg-danger-subtle"><i class="ti ti-cash"></i></a>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#free{{$user->id}}" class="btn  bg-warning"><i class="ti ti-percentage"></i></a>

                                        @if($user->status)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$user->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$user->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif

                                    </td>
                                </tr>
                                @include('admin.file.announcers.modal')
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
    
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                },
                "order": [[ 5, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection