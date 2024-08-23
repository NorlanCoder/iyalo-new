@extends('admin.template')

@section('title','Propriétés')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Propriétés
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
                                <th>Informations</th>
                                <th>Proprio</th>
                                <th>Localisation</th>
                                <th>Prix</th>
                                <th>Détail</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Informations</th>
                                <th>Proprio</th>
                                <th>Localisation</th>
                                <th>Prix</th>
                                <th>Détail</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($property->cover_url)}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $property->label }} <br> <b>{{ $property->category->label }}</b>  </h6>
                                        </div>
                                    </td>
                                    <td> <em>{{ $property->user->name }} <br> {{ $property->user->email }}</em> </td>
                                    <td> {{ $property->district }} - {{ $property->city }} - {{ $property->country }} </td>
                                    <td>
                                        {{ $property->price }} {{ $property->devise }} <br> Visite : {{ $property->visite_price }} {{ $property->devise }}
                                    </td>
                                    <td class="text-center">
                                        Chambre : {{ $property->room }} Douche : {{ $property->bathroom }} <br>
                                    </td>
                                    <td>
                                        @if($property->status)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $property->created_at }}</td>
                                    <td>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#detail{{$property->id}}" class="btn bg-warning-subtle"><i class="ti ti-info-circle"></i></a>

                                        @if($property->status)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$property->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$property->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.properties.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $properties->links() }}
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
                "order": [[ 6, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection