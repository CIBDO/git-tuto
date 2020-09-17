<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans['Editer les éléments'] }}
            </h4>
        </div>
        
        <form action="{{ url('labo_analyses/addChild') }}" class="form addChildForm" method="post">
        
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="childs">{{ trans['Veuillez selectionner les analyses associés'] }} :</label>
                    {{ selectStatic(['childs[]', childs, 'multiple': 'multiple', 'class': 'form-control', 'id': 'childs', 'class': 'childs', 'useEmpty': true, 'emptyText' : '']) }}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <button type="submit" class="btn btn-success pull-right addBtn" title="{{trans['Ajouter']}}">{{ trans['Ajouter'] }}</button>
            </div>

        </form>

    </div>
</div>

<script>
   var width = "100%"; //Width for the select inputs
    $(".childs").select2({
        width: width,
        allowClear: true,
        theme: "classic"
    });

</script>
