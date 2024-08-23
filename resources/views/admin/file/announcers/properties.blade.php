@extends('admin.template')

@section('announcer','active')
@section('title','Propriétés de '.$user->name )

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        <a href="{{route('admin.announcers')}}"> <i class="ti ti-corner-up-left"></i></a>
                        Historiques des Propreiétés de {{ $user->name }}
                    </h5>  
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Informations</th>
                                <th>Localisation</th>
                                <th>Prix</th>
                                <th>Détail</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Informations</th>
                                <th>Localisation</th>
                                <th>Prix</th>
                                <th>Détail</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($property->cover_url)}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $property->label }} <br> {{ $property->category->label }} </h6>
                                        </div>
                                    </td>
                                    <td> {{ $property->district }} - {{ $property->city }} - {{ $property->country }} </td>
                                    <td>
                                        {{ $property->price }} {{ $property->devise }} <br> Visite : {{ $property->visite_price }} {{ $property->devise }}
                                    </td>
                                    <td class="text-center">
                                        Chambre : {{ $property->room }} Douche : {{ $property->bathroom }} <br>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#detail{{$property->id}}" class="btn btn-warning"><i class="ti ti-info-circle"></i></a>
                                    </td>
                                    <td>
                                        @if($property->status)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $property->created_at }}</td>
                                </tr>
                                
                                <div class="modal fade" id="detail{{$property->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h3 class="modal-title" id="staticBackdropLabel" >Profil de {{ $property->label }} </h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                    <div class="row">
                                                        <h5>Description</h5>
                                                        <div class="col-sm-12">
                                                            {{ $property->room }} chambres ;  {{ $property->bathroom }} douches ;  {{ $property->lounge }} salons ;  {{ $property->swingpool }} piscine
                                                        </div>
                                                        <div class="col-sm-12">
                                                            {{ $property->description }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <h5>Conditions</h5>
                                                        <div class="col-sm-12">
                                                            {{ $property->conditions }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <h5>Images</h5>
                                                        @foreach($property->media($property->id) as $url)
                                                            <div class="col-sm-4">
                                                                <img src="{{ asset($url) }}" alt="img" style="width: 100%">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                "order": [[ 5, 'desc' ]],
            });
        });
    </script>
@endsection