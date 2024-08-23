@extends('admin.template')

@section('announcer','active')
@section('title','Porteuille de '.$user->name )

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4"> 
                        <a href="{{route('admin.announcers')}}"> <i class="ti ti-corner-up-left"></i></a>
                        Historiques du Porteuille de {{ $user->name }}
                    </h5>  
                </div> 
                <br>
                <div class="row">
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-warning-subtle btn-lg btn-block">
                           <b>Montant Total:</b> <br> {{ number_format($cash) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-danger-subtle btn-lg btn-block">
                           <b>Montant Retirer :</b> <br> {{ number_format($withdrawal) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class=" btn btn-rounded bg-success-subtle btn-lg btn-block">
                           <b>Montant Disponible:</b> <br> {{ number_format($wallet) }} XOF
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td class="text-center">
                                        {{ $withdraw->reference }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($withdraw->amount) }} XOF
                                    </td>
                                    <td  class="text-center">
                                        @if($withdraw->is_confirm)
                                            <span class="badge bg-success"> Oui </span>
                                        @else
                                            <span class="badge bg-danger"> Non </span>
                                        @endif 
                                    </td>
                                    <td>{{ $withdraw->created_at }}</td>
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
                "order": [[ 3, 'desc' ]],
            });
        });
    </script>
@endsection