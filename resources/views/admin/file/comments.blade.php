@extends('admin.template')

@section('title','Commentaires')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                    Commentaires
                    </h5>
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Describ</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Describ</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset('profile/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $comment->name }} <br> {{ $comment->function }} </h6>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $comment->phone }}
                                    </td>
                                    <td>
                                        @if($comment->is_active)
                                            <span class="badge bg-success"> Actif </span>
                                        @else
                                            <span class="badge bg-danger"> Bloqué </span>
                                        @endif
                                    </td>
                                    <td>{{ $comment->created_at }}</td>
                                    <td>
                                    @if($comment->is_active)
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$comment->id}}" class="btn btn-danger"><i class="ti ti-lock"></i></a>
                                        @else
                                            <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$comment->id}}" class="btn btn-success"><i class="ti ti-lock-open"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="action{{$comment->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-{{ $comment->is_active ? 'danger' : 'success'}}">
                                                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{ $comment->is_active ? 'Bloquer' : 'Débloquer'}}?</h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                                                <br>
                                                <form action="{{ route('admin.comments.action',$comment->id) }}" method="post" >
                                                    @csrf
                                                    @method('put')
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-{{ $comment->is_active ? 'danger' : 'success'}}">Valider</button>
                                                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </form>
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
                // "order": [[ 5, 'desc' ]],
            });
            
        });
    </script>
@endsection