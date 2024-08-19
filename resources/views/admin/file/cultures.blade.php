@extends('admin.template')

@section('title','Cultures')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Cultures
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
                                    <td> {{ $culture->site->user->lastname }} {{ $culture->site->user->firstname }} <br> <b>Culture : {{ $culture->site->name }}</b> </td>
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