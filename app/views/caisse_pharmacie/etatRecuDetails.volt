<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Liste des ventes / Détails"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Caisse pharmacie"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Liste des ventes'] }}</li>
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

                            <div class="row">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Du ']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2">{{ trans['Au ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                </div>

                                <div class="form-group  col-md-2" style="margin-right : 10px">
                                    <label for="etat">{{ trans['Etat']}} :</label>
                                    {{ select('etat',  ['1' : 'Validé', '0' : 'Annulé'], 'useEmpty' : false, 'class': 'form-control', 'required' : 'required') }}
                                </div>

                                <div class="form-group  col-md-3">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation" title="{{trans['Recherche']}}">
                                        <i class="fa fa-filter"></i> {{trans['Filtrer']}}
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

    function idFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs col-xs-12 " href="{{ url("print/recuMedicament/") }}' + value + '"><i class="fa fa-search pull-left"></i><span class="pull-right">' + value + '</span></a>';
    }
    function cancelFormatter(value, row, index) {
        if(row.etat == 1){
            return '<a class="btn btn-warning btn-xs cancelTicket" data-ticketid="' + row.id + '"><i class="fa fa-trash pull-left"></i>Annuler</a>';
        }
        else{
            return "";
        }
    }

    function montant_patientFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_montant_patient, 10);
        }
        return numberFormatter(total);
    }
    function montant_normalFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_montant_normal, 10);
        }
        return numberFormatter(total);
    }
    function montant_restantFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_reste, 10);
        }
        return numberFormatter(total);
    }


    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        $('#table-javascript').bootstrapTable({
            data: {{ recus }},
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
            showMultiSort: true,
            showPaginationSwitch: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            exportDataType : "selected",
            mobileResponsive: true,
            filterControl: true,
            showColumns: true,
            showFooter: true,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'id',
                    title: "{{ trans['N° Ticket'] }}",
                    sortable: true,
                    filterControl: "input",
                    formatter: idFormatter,
                },
                {
                    field: 'date',
                    title: "{{ trans['Date  de création'] }}",
                    sortable: true,
                    formatter:date2Formatter
                },
                {
                    field: 'caissier_nom',
                    title: "{{ trans['Caissier'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'patient_id',
                    title: "{{ trans['ID Patient'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'patients_nom',
                    title: "{{ trans['Patient'] }}",
                    sortable: true,
                    filterControl: "input",
                    footerFormatter: function(){return 'Total';}
                },
                {
                    field: 'p_libelle',
                    title: "{{ trans['Produit'] }}",
                    sortable: true,
                    filterControl: "input",
                },
                {
                    field: 'd_quantite',
                    title: "{{ trans['Quantité'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'd_montant_normal',
                    title: "{{ trans['Total'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_normalFooter
                },
                {
                    field: 'd_montant_patient',
                    title: "{{ trans['Montant Payé'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_patientFooter
                },
                {
                    field: 'd_reste',
                    title: "{{ trans['Reste à <br> payer'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_restantFooter
                },
                {
                    field: 'assurance_libelle',
                    title: "{{ trans['Tierc payant'] }}",
                    sortable: true,
                    filterControl: "input",
                },
                {
                    field: 'assurance_taux',
                    title: "{{ trans['Taux'] }}",
                    align: 'right',
                    sortable: true,
                    filterControl: "input"
                }
            ]
        });
    });
</script>