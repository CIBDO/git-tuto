<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                {{ trans["Importer un modele"] }}
            </h4>
        </div>
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="content">
                    <div class="table-responsive">
                        <table  id="modeleTable">
                            <thead class="bg-aqua-gradient">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs

    function actionsFormatter(value, row, index) {
        return '<a class="btn btn-default ajax-navigation" title="{{ trans["Importer"] }}" href="{{url("img_demandes/editDossier2/")}}' + {{patients_id}} + "/" +{{dossier_id}} + '/'+ row.id +'" data-modeleid="' + row.id + '"><i class="fa fa-level-down"></i></a>';
    }

    $('#modeleTable').bootstrapTable({
        data: {{ imgModele }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "id",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showRefresh: false,
        showFooter: false,
        showLoading: true,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: true,
        rowStyle: rowStyle,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'keyword',
                title: "{{ trans['Mots clés'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'keyword2',
                title: "{{ trans['Mots clés'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'interpretation',
                title: "{{ trans['Interpretation'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'conclusion',
                title: "{{ trans['Conclusion'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'id',
                title: "Actions",
                align: "center",
                formatter: actionsFormatter,
            }
        
        ]
    });

</script>
