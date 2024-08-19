@extends('admin.template')

@section('title','Notifications')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Historiques des notifications
                    </h5>  
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Agriculteur</th>
                                <th>Message</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Agriculteur</th>
                                <th>Message</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($notifs as $notif)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <h6 class="mb-0 text-center"> {{ $notif->user->lastname }} {{ $notif->user->firstname }}
                                        </div>
                                    </td>
                                    <td> {{ $notif->alert }}</td>
                                    <td>
                                        @if($notif->read)
                                            <span class="badge bg-success"> Lu </span>
                                        @else
                                            <span class="badge bg-danger"> Non Lu </span>
                                        @endif
                                    </td>
                                    <td>{{ $notif->created_at }}</td>
                                    <td>
                                        @if($notif->read)
                                            <a href="{{ route('admin.notifications.read', $notif->id) }}" class="btn btn-danger" title="Masquer comme non-lu"><i class="ti ti-mail"></i></a>
                                        @else
                                        <a href="{{ route('admin.notifications.read', $notif->id) }}" class="btn btn-success" title="Masquer comme lu"><i class="ti ti-mail-opened"></i></a>
                                        @endif
                                    </td>
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