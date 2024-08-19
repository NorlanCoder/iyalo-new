@extends('admin.template')

@section('title','Souscription')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title fw-semibold mb-4">
                        Souscription
                    </h5>
                </div> 
                <br>
                <div class="table-responsive">
                    <table id="example" class="table border table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Phone</th>
                                <th>Describ</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Phone</th>
                                <th>Describ</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="{{asset($contact->url_profile ?: 'profile/user-1.jpg')}}" width="45"
                                            class="rounded-circle" />
                                            <h6 class="mb-0 text-center"> {{ $contact->name }} <br> {{ $contact->email }} </h6>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $contact->phone }}
                                    </td>
                                    <td>
                                        <b>{{ $contact->objet }}</b><br>
                                        {{ $contact->description }}
                                    </td>
                                    <td>{{ $contact->created_at }}</td>
                                    <td>
                                        <a type="button"  data-bs-toggle="modal" data-bs-target="#action{{$contact->id}}" class="btn btn-primary"><i class="ti ti-user"></i></a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="action{{$contact->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h3 class="modal-title text-white" id="staticBackdropLabel">Valider de souscription</h3>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Voulez-vous le défini comme un <b>Agriculteur</b>?</h5>
                                                <br>
                                                <form action="{{ route('admin.validate',$contact->id) }}" method="post" >
                                                    @csrf
                                                    @method('put')
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-primary">Valider</button>
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