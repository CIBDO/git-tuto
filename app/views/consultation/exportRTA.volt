<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Export RTA"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Consultations"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['formulaires'] }}</li>
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
                                <div class="form-group  col-md-4" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Date entre']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-4" style="margin-right : 10px">
                                    <label for="date2">{{ trans['et ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
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

</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(new Date(value).getTime()/1000, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function idFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs col-xs-12 " href="{{ url("print/ticket/") }}' + value + '"><i class="fa fa-search pull-left"></i><span class="pull-right">' + value + '</span></a>';
    }
    function detailsFormatter(value, row, index) {
        return '<a class="btn btn-info btn-xs "href=\'{{ url("consultation/consultation/") }}'+row.patient_id+'\'"><i class="fa fa-fw fa-stethoscope"></i> {{trans["Détails"]}}</a>';
    }

    function montant_patientFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_patient, 10);
        }
        return total;
    }
    function montant_normalFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_normal, 10);
        }
        return total;
    }
    function montant_restantFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_restant, 10);
        }
        return total;
    }

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });


        $('#table-javascript').bootstrapTable({
            data: {{ result }},
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
            showMultiSort: false,
            showPaginationSwitch: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            exportDataType : "selected",
            mobileResponsive: true,
            filterControl: true,
            showColumns: true,
            showFooter: false,
            rowStyle: rowStyle,
            columns: {{ columns }}
        });
    });
</script>