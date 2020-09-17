<div class="example-modal">
    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="background-image: url('{{ url("img/graph.png") }}'); background-size: cover; background-size: 115px 115px; background-repeat: no-repeat; background-position: right top">
                    <div class="row">
                        <div class="col-md-9">
                            <h3>{{notif.title}}</h3>
                            <p> {{notif.message}}</p>
                        </div>
                        <!--<div class="col-md-3">
                            <img src="{{ url("img/tag-euro.png") }}" width="100" >
                        </div>-->
                    </div>
                    <button type="button" class="btn btn-danger btn-block btn-lg">Accéder aux transactions</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>