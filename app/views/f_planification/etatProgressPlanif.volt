<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Suivi des prévisions budgétaires"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Planification"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Année']}} :</label>
                                        {{ select(['annee',  annee, 'class': 'form-control', 'id' : 'annee', 'useEmpty' : true, 'emptyText' : 'Choisir']) }}
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-left" title="{{trans['Recherche']}}">
                                        <i class="fa fa-fw fa-filter"></i> {{trans['Filtrer']}}
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

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Suivi des prévisions par type de prévision</h3>
                    </div>
                    <div class="box-body">
                        <div id="toolbar">
                        </div>

                        <div class="content">
                            <div class="table-responsive">
                                <table  id="progressByTypeCompte">
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

        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Suivi des prévisions par compte</h3>
                    </div>
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
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index) {
        return '<a class="editPopup" title="{{ trans["edit FPlanification"] }}" href="#" data-toggle="modal" data-target="#editFPlanification" data-fplanification="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["delete FPlanification"] }}" href="#" data-fplanification="' + value + '" data-fplanificationname="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
    }

    function rowStyle(row, index) {
        var classes = ['info'];
        if (index % 2 === 0) {
            return {
                classes: classes[0]
            };
        }
        return {};
    }



    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs

        $('body').on('click', '.createPopup', function () {
            $.ajax({
                url: "{{url('f_planification/createFPlanification')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createFPlanification').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('f_planification/editFPlanification')}}/" + $(this).data('fplanification'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editFPlanification').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var fplanification_id = $(this).data('fplanification');
            var currentTr = $(this).closest("tr");
            swal(
                    {
                        title: '{{ trans["Are you sure?"] }}',
                        text: "{{ trans['Cette action supprimera cet element de la planification'] }} ",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans["Yes, delete it!"] }}',
                        cancelButtonText: '{{ trans["No, cancel!"] }}',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm === true) {
                            $('body').addClass('loading');
                            $.ajax({
                                url: "{{url('f_planification/deleteFPlanification')}}/" + fplanification_id,
                                cache: false,
                                async: true
                            })
                            .done(function( result ) {
                                console.log(result);
                                $('body').removeClass('loading');
                                if(result == "1"){
                                    swal(
                                        '{{ trans["Deleted!"] }}',
                                        "{{ trans["L'element a été supprimé de la planification."] }}",
                                        'success'
                                    );
                                    $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                                    $('body').addClass('loading');
                                    window.location.reload();
                                }
                                else{
                                    swal(
                                        '{{ trans["Cancelled!"] }}',
                                        "{{ trans['Cancelled'] }}",
                                        'error'
                                    );
                                }
                            });
                        } else if (isConfirm === false) {
                            
                        } else {
                        // Esc, close button or outside click
                        // isConfirm is undefined
                        }
                    }
            );
        });

        function progressBarFormatter(value, row){
            if(row.type_compte == 'depense'){
                var color = 'success';
                if(value >= 70 && value <= 90)
                    color = 'warning';
                if(value > 90)
                    color = 'danger';
                return '<div class="progress progress-xs progress-striped active">' +
                          '<div class="progress-bar progress-bar-'+color+'" style="width: '+value+'%"></div>' +
                        '</div>';
            }
            if(row.type_compte == 'recette'){
                var color = 'primary';
                if(value >= 80)
                    color = 'success';
                return '<div class="progress progress-xs progress-striped active">' +
                          '<div class="progress-bar progress-bar-'+color+'" style="width: '+value+'%"></div>' +
                        '</div>';
            }
        }

        function pourcentLabelFormatter(value, row){
            if(row.type_compte == 'depense'){
                var color = 'green';
                if(row.progression >= 70 && row.progression <= 90)
                    color = 'yellow';
                if(row.progression > 90)
                    color = 'red';
                return '<span class="badge bg-'+color+'">'+row.progression+'%</span>';
            }
            if(row.type_compte == 'recette'){
                var color = 'blue';
                if(row.progression >= 80)
                    color = 'green';
                return '<span class="badge bg-'+color+'">'+row.progression+'%</span>';
            }
        }

        function typeCompteFormatter(value, row){
            if(value == "depense"){
                return "Dépense";
            }
            return "Récette";
        }

        $('#table-javascript').bootstrapTable({
            data: {{ progressByCompte }},
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
                    field: 'type_prevision',
                    title: "{{ trans['Type de prévision'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'type_compte',
                    title: "{{ trans['Type de compte'] }}",
                    sortable: true,
                    filterControl: "input",
                    formatter: typeCompteFormatter
                },
                {
                    field: 'compte_numero',
                    title: "{{ trans['Compte'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'compte_libelle',
                    title: "{{ trans['Libellé du compte'] }}",
                    filterControl: "input",
                    sortable: true,
                },
                {
                    field: 'montant_planif',
                    title: "{{ trans['Montant Prévu'] }}",
                    align: 'right',
                    sortable: true,
                    formatter: amountFormatter,
                },
                {
                    field: 'montant_op',
                    title: "{{ trans['Montant Opéré'] }}",
                    align: 'right',
                    sortable: true,
                    formatter: amountFormatter,
                },
                {
                    field: 'progression',
                    title: "{{ trans['Progression'] }}",
                    align: 'center',
                    sortable: true,
                    formatter: progressBarFormatter
                },
                {
                    title: "{{ trans[' '] }}",
                    align: 'right',
                    formatter: pourcentLabelFormatter
                }
            ]
        });

        $('#progressByTypeCompte').bootstrapTable({
            data: {{ progressByTypePrevision }},
            cache: false,
            striped: true,
            pagination: false,
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
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'type_prevision',
                    title: "{{ trans['Type de prévision'] }}",
                    sortable: true,
                },
                {
                    field: 'type_compte',
                    title: "{{ trans['Type de compte'] }}",
                    sortable: true,
                    formatter: typeCompteFormatter
                },
                {
                    field: 'montant_planif',
                    title: "{{ trans['Montant Prévu'] }}",
                    align: 'right',
                    sortable: true,
                    formatter: amountFormatter,
                },
                {
                    field: 'montant_op',
                    title: "{{ trans['Montant Opéré'] }}",
                    align: 'right',
                    sortable: true,
                    formatter: amountFormatter,
                },
                {
                    field: 'progression',
                    title: "{{ trans['Progression'] }}",
                    align: 'center',
                    sortable: true,
                    formatter: progressBarFormatter
                },
                {
                    title: "{{ trans[' '] }}",
                    align: 'right',
                    formatter: pourcentLabelFormatter
                }
            ]
        });

        submitAjaxForm();


    });
</script>