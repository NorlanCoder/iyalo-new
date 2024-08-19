@extends('admin.template')

@section('title','Site Agricole')


@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques de tous les sites
                    </h5>
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Informations</th>
                                <th>Utilisateur</th>
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
                                <th>Utilisateur</th>
                                <th>Adresse</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($sites as $site)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($site->image ?: 'site/images.jpeg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $site->name }} <br> <b>Sol:</b>{{ $site->sol }} <br>
                                        {{ $site->superficie }}m²</h6>
                                        </div>
                                    </td>
                                    <td> {{ $site->user->lastname }} {{ $site->user->firstname }}</td>
                                    <td> {{ $site->address ?:'---' }} {{ $site->ville ?:'---'}}</td>
                                    <td>
                                        {{ $site->description ?:'---' }}
                                    </td>
                                    <td>
                                        @if($site->is_active)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $site->created_at }}</td>
                                    <td>
                                        <a href="{{route('admin.users.cultures', $site->id)}}" class="btn btn-primary" title="Mes Cultures"><i class="ti ti-eye"></i></a>
                                        
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#edit{{$site->id}}" class="btn btn-warning"><i class="ti ti-edit"></i></a>
                                        @if($site->is_active)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$site->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$site->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.users.sites_modal')
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