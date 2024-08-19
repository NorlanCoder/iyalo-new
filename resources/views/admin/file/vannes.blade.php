@extends('admin.template')

@section('title','Vannes')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Vannes
                    </h5>  
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Propriétaire</th>
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
                                <th>Propriétaire</th>
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
                                            <h6 class="mb-0 text-center"> {{ $vanne->culture->site->user->lastname }} {{ $vanne->culture->site->user->firstname }} <br> <b>Site : {{ $vanne->culture->site->name }}</b> <br> <b>Culture : {{ $vanne->culture->name }}</b>
                                        </div>
                                    </td>
                                    <td> {{ $vanne->code }}</td>
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