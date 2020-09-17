<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Stock - point de distribution "] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Stock"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
   
    <!-- Main content -->
    <section class="content">

        <!-- Main row -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">

                        <div id="toolbar" class="btn-group">
                            <button type="button" class="btn btn-default createDistribution" title="{{trans["distribution ce produit dans un aun autre autre point"]}}">{{trans["Distribuer"]}}</button>
                        </div>

                        <div class="content">
                            <div class="table-responsive">
                                <table  id="table-javascript">
                                    <thead class="bg-aqua-gradient">
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <div id="createDistribution" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index) {
        return '<a class="btn btn-primary createDistribution" href="#" title="{{trans["distribuer ce produit"]}}" data-toggle="modal" data-target="#createDistribution" data-stockid="' + value + '" data-stockreste="' + row.reste + '">{{trans["distribuer"]}}</a><a class="btn btn-warning createAjustement" href="#" title="{{trans["Ajuster ce produit dans ce point de vente"]}}" data-toggle="modal" data-target="#createAjustement" data-stockid="' + value + '" data-stockreste="' + row.reste + '">{{trans["Ajuster"]}}</a>';
    }

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        /*$('body').on('click', '.createDistribution', function () {
            $.ajax({
                url: "{{url('approvisionnement/createDistribution')}}/" + $(this).data('stockid') + "/" + $(this).data('stockreste'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createDistribution').html(html);
            });
        });
*/
        
        $('body').on('click', '.createDistribution', function () {
            var selectedRows = $('#table-javascript').bootstrapTable('getAllSelections');
            if(selectedRows.length == 0){
                swal('{{ trans["Oops!"] }}', 'Aucun produit sélectionné', 'warning');
                return;
            }
            var txtID = [];
            for (var i = 0; i < selectedRows.length; i++) {
                txtID[i]=selectedRows[i].id;
            }
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('stock_point_distribution/createDistribution')}}/" + txtID.join(","),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createDistribution').html(html);
                $('#createDistribution').modal('show');
            });
        });

        $('body').on('click', '.suppElement', function () {
            var current = $(this).closest("tr");
            $(current).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
        });

    
        $('#table-javascript').bootstrapTable({
            data: {{ stockPointDistributions }},
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
            clickToSelect: true,
            toolbar: "#toolbar",
            showFooter: false,
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
                    field: 'point_distribution',
                    title: "{{ trans['Point de distribution'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'produit_libelle',
                    title: "{{ trans['Produit'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'reste',
                    title: "{{ trans['Stock'] }}",
                    sortable: true,
                },
                {
                    field: 'lot',
                    title: "{{ trans['Lot'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'date_peremption',
                    title: "{{ trans['Date de peremption'] }}",
                    sortable: true,
                    formatter: date2OnlyFormatter,
                    filterControl: "input"
                }/*,
                {
                    field: 'id',
                    title: "Actions",
                    align: "center",
                    formatter: actionsFormatter,
                }*/
            ]
        });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>