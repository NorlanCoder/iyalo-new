@extends('admin.template')

@section('title','Notes')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des Notes sur Propriétés
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
                                <th>Clients</th>
                                <th>Propriétés</th>
                                <th>Proprio</th>
                                <th>Note</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Clients</th>
                                <th>Propriétés</th>
                                <th>Proprio</th>
                                <th>Note</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($notes as $note)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <h6 class="mb-0 text-center"> {{ $note->user->name }} <br> {{ $note->user->email }}
                                        </div>
                                    </td>
                                    <td> {{ $note->property->label }} <br> <b>{{ $note->property->category->label }}</b></td>
                                    <td> {{ $note->property->user->name }} <br> {{ $note->property->user->email }} </td>
                                    <td>
                                    {{ $note->comment }}
                                    </td>
                                    <td>{{ $note->created_at }}</td>
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
                "order": [[ 5, 'desc' ]],
                paging:   false,
                info:   false,
                responsive: true,
                "searching": false
            });
        });
    </script>
@endsection