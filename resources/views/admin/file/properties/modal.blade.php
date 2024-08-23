<!-- Action -->
<div class="modal fade" id="action{{$property->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $property->status ? 'danger' : 'success'}}">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous {{ $property->status ? 'Bloquer' : 'Débloquer'}}?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.properties.action',$property->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $property->status ? 'danger' : 'success'}}">Valider</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                                
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
                        <h5>Calendrier de la semaine</h5>
                        <div class="col-sm-12">
                            <div class="row">
                                @if(count($property->calendar($property->id)) != 0)
                                    @foreach($property->calendar($property->id) as $calendar)
                                        <div class="col-sm-4">
                                            <b>Jour :</b> {{$calendar->day}} <br>
                                            <b>heure :</b> {{$calendar->hour['start']}} à {{$calendar->hour['end']}} heures
                                        </div>
                                    @endforeach
                                @else
                                    Aucun calendrier de la semaine disponible
                                @endif
                            </div>
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