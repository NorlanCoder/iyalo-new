@extends('admin.template')

@section('title','Transaction Sortants')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Transactions Sortantes
                    </h5>
                </div> 
                <br>
                <div class="row">
                    <div class="col-sm-3">
                        <div class=" btn btn-rounded bg-primary-subtle btn-lg btn-block">
                           <b>Frais IYALO:</b> <br> {{ number_format($all_cash) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class=" btn btn-rounded bg-warning-subtle btn-lg btn-block">
                           <b>Montant Total:</b> <br> {{ number_format($cash) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class=" btn btn-rounded bg-danger-subtle btn-lg btn-block">
                           <b>Montant Retirer :</b> <br> {{ number_format($withdrawal) }} XOF
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class=" btn btn-rounded bg-success-subtle btn-lg btn-block">
                           <b>Montant Disponible:</b> <br> {{ number_format($wallet) }} XOF
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
                                <th>Utilisateur</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Reférence</th>
                                <th>Montant</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($withdraw->user->image_url ?: 'uploads/profil/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $withdraw->user->name }} <br> {{ $withdraw->user->email }} </h6>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $withdraw->reference }}
                                    </td>
                                    <td class="text-center">
                                        {{ $withdraw->phone }} <br>
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
                                    <td>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#check{{$withdraw->id}}" class="btn bg-success-subtle"><i class="ti ti-checkbox"></i></a>
                                    </td>
                                </tr>
                                @include('admin.file.withdraws.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $withdraws->links() }}
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
                "order": [[ 4, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection