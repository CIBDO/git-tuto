<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Ajustements"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Ajustement"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Du ']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2">{{ trans['Au ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-left" title="{{trans['Recherche']}}">
                                        <i class="fa fa-fw fa-filter"></i> {{trans['Filtrer']}}
                                    </button>
                                    <button type="button" class="btn btn-info createPopup pull-right" data-toggle="modal" data-target="#createAjustement">
                                    {{trans['Faire un ajustement']}}
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <div id="toolbar">
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

    <div id="createAjustement" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        $('body').on('click', '.createPopup', function () {
            $.ajax({
                url: "{{url('ajustement/createAjustement')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createAjustement').html(html);
            });
        });

        $('body').on('click', '.searchProduit', function () {

            if($("#s_idproduit").val() == "" || $("#type").val() == ""){
                sweetAlert("Oops...", "Veuillez saisir les critères de recherche", "warning");
                return;
            }
            var val = $("#type").val();
            if( (val == "ajout") && ($("#s_lot").val() == "") ){
                sweetAlert("Attention!", "Le lot est obligatoire pour un ajustement en 'ajout'", "warning");
                return;
            }
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('ajustement/searchProduitStock')}}/" + $("#type").val() + "/" + $("#s_idproduit").val() + "/" + $("#s_lot").val(),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#s_resultat').html(html);
            });
        });


        $('#table-javascript').bootstrapTable({
        data: {{ ajustements }},
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
                field: 'date',
                title: "{{ trans["Date de l'ajustement"] }}",
                sortable: true,
                formatter:date2Formatter
            },
            {
                field: 'type',
                title: "{{ trans['Type'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'motif',
                title: "{{ trans['Motif'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'point_distribution',
                title: "{{ trans['Point de distribution'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'produit_libelle',
                title: "{{ trans['Produit'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'quantite',
                title: "{{ trans['Quantité'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'lot',
                title: "{{ trans['Lot'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de péremption'] }}",
                align: "center",
                filterControl: "input"
            }
        ]
    });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>