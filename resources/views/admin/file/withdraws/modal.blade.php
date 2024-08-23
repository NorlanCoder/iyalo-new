
<div class="modal fade" id="check{{$withdraw->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title" id="staticBackdropLabel" >Voulez-vous valider le retrait?</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Cliquer sur valider pour éffectuer votre action</h5>
                <br>
                <form action="{{ route('admin.withdraws.check',$withdraw->id) }}" method="post" >
                    @csrf
                    @method('put')
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Oui</button>
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Non</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>