@extends('admin.template')

@section('title','Visites')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Visites
                    </h5>
                </div> 
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-warning-subtle btn-lg btn-block">
                           <b>Montant Total:</b> <br> {{ number_format($all_cash) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-danger-subtle btn-lg btn-block">
                           <b>Montant en attente :</b> <br> {{ number_format($pending) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-success-subtle btn-lg btn-block">
                           <b>Montant Disponible:</b> <br> {{ number_format($cash) }} XOF
                        </div>
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
                                <th>Visiteur</th>
                                <th>Propriétés</th>
                                <th>Proprio</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Lieu Visité</th>
                                <th>Remboursé</th>
                                <th>Date Visite</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Visiteur</th>
                                <th>Propriétés</th>
                                <th>Proprio</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Lieu Visité</th>
                                <th>Remboursé</th>
                                <th>Date Visite</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($visit->user->image_url ?: 'uploads/profil/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $visit->user->name }} <br> {{ $visit->user->email }} </h6>
                                        </div>
                                    </td>
                                    <td class="text-center"> {{ $visit->property->label }} <br> <b>{{ $visit->property->category->label }}</b>  </td>
                                    <td class="text-center"> {{ $visit->property->user->name }} <br> <b>{{ $visit->property->user->email }}</b>  </td>
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
                                    <td>
                                        @if($visit->visited)
                                            <span class="badge bg-success"> VALIDE </span>
                                        @else
                                            @if($visit->is_refund)
                                                <span class="badge bg-danger"> REMBOURSER </span>
                                            @else
                                                <a type="button"  data-bs-toggle="modal" data-bs-target="#refund{{$visit->id}}" class="btn bg-warning-subtle"><i class="ti ti-receipt-refund"></i></a>
                                                
                                                <a type="button"  data-bs-toggle="modal" data-bs-target="#check{{$visit->id}}" class="btn bg-success-subtle"><i class="ti ti-checkbox"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.file.visits.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $visits->links() }}
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