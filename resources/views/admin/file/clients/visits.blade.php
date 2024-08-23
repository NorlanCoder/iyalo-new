@extends('admin.template')

@section('clients','active')
@section('title','Visites de '.$user->name )

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4"> 
                        <a href="{{route('admin.clients')}}"> <i class="ti ti-corner-up-left"></i></a>
                        Historiques des Visites de {{ $user->name }}
                    </h5>  
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Proprio</th>
                                <th>Propriétés</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Lieu Visité</th>
                                <th>Remboursé</th>
                                <th>Date Visite</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Proprio</th>
                                <th>Propriétés</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Lieu Visité</th>
                                <th>Remboursé</th>
                                <th>Date Visite</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($visit->user->image_url ?: 'uploads/profil/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $visit->property->user->name }} <br> {{ $visit->property->user->email }} </h6>
                                        </div>
                                    </td>
                                    <td class="text-center"> {{ $visit->property->label }} <br> <b>{{ $visit->property->category->label }}</b>  </td>
                                    <td class="text-center">
                                        {{ $visit->reference }}
                                    </td>
                                    <td class="text-center">
                                        <b>Payé :</b> {{ $visit->amount }} {{ $visit->property->device }} <br>
                                        <b>Frais :</b> {{ $visit->free }} {{ $visit->property->device }} <br>
                                        <b>Dispo :</b> {{ $visit->amount - $visit->free }} {{ $visit->property->device }}
                                    </td>
                                    <td  class="text-center">
                                        @if($visit->visited)
                                            <span class="badge bg-success"> Oui </span>
                                        @else
                                            <span class="badge bg-danger"> Non </span>
                                        @endif 
                                        <br>
                                        {{$visit->describ}}
                                    </td>
                                    <td  class="text-center">
                                        @if($visit->is_refund)
                                            <span class="badge bg-success"> Oui </span>
                                        @else
                                            <span class="badge bg-danger"> Non </span>
                                        @endif
                                    </td>
                                    <td>{{ $visit->date_visite }}</td>
                                </tr>
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
                "order": [[ 6, 'desc' ]],
            });
        });
    </script>
@endsection