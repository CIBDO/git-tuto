<div class="example-modal">
    <div class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="
                     padding-bottom: 2px;
                     padding-top: 2px;
                     ">
                    <div class="row">
                        <div class="col-md-7"><h3>{{notif.title}}</h3></div>
                        <div class="col-md-5"><div class="pull-right"><img src="{{ url("img/retail_icon.png") }}" height="80" ></div></div>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-11">
                           
                           {{notif.message}}
                        </div>
                        <!--<div class="col-md-3">
                            <img src="{{ url("img/tag-euro.png") }}" width="100" >
                        </div>-->
                    </div>
                    <button type="button" class="btn btn-info btn-block btn-lg">Acceder aux transactions</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>