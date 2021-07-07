<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Recherche de dossier patient'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Dossier patient']}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['recherche'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12" >
            {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                <a type="button" class="btn btn-primary pull-right createPopup" href="{{url('patients/form')}}">
                    <i class="fa fa-plus"></i> {{trans['Créer un dossier patient']}}
                </a>
            {% endif %}
            </div>
        </div>

         <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline form-caisse" role="form" action="" method="get">
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="Services" style="width: 100px">{{ trans['ID']}} :</label>
                                        {{ textField(['id', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                       <label for="Services" style="width: 100px">{{ trans['ID technique']}} :</label>
                                        {{ textField(['id_technique', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                         
                                    </div>
                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                       <label for="date1" style="width: 100px">{{ trans['Créé entre le']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="date2" style="width: 100px">{{ trans['et ']}} :</label>
                                        {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                    </div>

                                   <div class="form-group  col-md-4">
                                        
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                         <label for="nom" style="width: 100px">{{ trans['Nom']}} :</label>
                                        {{ textField(['nom', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                         <label for="prenom" style="width: 100px">{{ trans['Prénom']}} :</label>
                                        {{ textField(['prenom', 'class': 'form-control']) }}
                                    </div>

                                   <div class="form-group  col-md-4">
                                       <label for="date_naissance" style="width: 100px">{{ trans['Naissance']}} :</label>
                                        {{ dateField(['date_naissance', 'class': 'form-control', 'id': 'date_naissance']) }}
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >

                                    <div class="form-group  col-md-4">
                                        <label for="telephone" style="width: 100px">{{ trans['Téléphone']}} :</label>
                                        {{ textField(['telephone', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="telephone2" style="width: 100px">{{ trans['Autre téléphone']}} :</label>
                                        {{ textField(['telephone2', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                       <label for="residence_id" style="width: 100px">{{ trans['Résidence']}} :</label>
                                        {{ selectStatic(['residence_id', residences, 'using' : ['id', 'libelle'], 'class': 'form-control', 'id': 'residence_id', 'useEmpty': true]) }}
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >

                                    <div class="form-group  col-md-4">
                                       <label for="adresse" style="width: 100px">{{ trans['Adresse']}} :</label>
                                        {{ textField(['adresse', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="sexe" style="width: 100px">{{ trans['Sexe']}} :</label>
                                        {{ select(['sexe',  ['m' : 'Masculin', 'f' : 'Feminin'], 'useEmpty' : true, 'class': 'form-control', 'id' : '_sexe']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="ethnie" style="width: 100px">{{ trans['Ethnie']}} :</label>
                                        {{ textField(['ethnie', 'class': 'form-control']) }}
                                    </div>
                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    
                                    <div class="form-group  col-md-4">
                                        
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <button type="submit" class="btn btn-default pull-right" title="{{trans['Recherche']}}">
                                            <i class="fa fa-search"></i> {{trans['Chercher']}}
                                        </button>
                                    </div>

                                    <div class="form-group  col-md-4">
                                        
                                    </div>
                                        
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

    <div id="createSthing" class="modal fade" role="dialog"></div>
</div>

<script>

    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function ticketFormatter(value, row, index){
         return '<a class="btn btn-primary btn-xs" title="{{ trans["Details"] }}" href="{{url("caisse/index")}}/'+row.id+'"><i class="fa fa-plus"></i>&nbsp; {{ trans["Ticket"] }}</a>';
    }
    function pharmacieFormatter(value, row, index){
         return '<a class="btn btn-primary btn-xs" title="{{ trans["Details"] }}" href="{{url("caisse_pharmacie/index")}}/'+row.id+'"><i class="fa fa-plus"></i>&nbsp; {{ trans["Pharmacie"] }}</a>';
    }
    function actionsFormatter(value, row, index){
         return '<a class="btn btn-info btn-xs" title="{{ trans["Details"] }}" href="{{url("patients/dossier")}}/'+row.id+'"><i class="fa fa-search"></i>&nbsp; {{ trans["Details"] }}</a>';
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

        var width = "200px"; //Width for the select inputs
        var select2Residence = $("#residence_id").select2({
            width: width,
            placeholder: 'Selectionnez',
            allowClear: true,
            theme: "classic"
        });
        $("#_sexe").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('services/editService')}}/" + $(this).data('service'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editService').html(html);
            });
        });
        var _data = {{ patients }};
        $('#table-javascript').bootstrapTable({
        data: _data,
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
        showColumns: true,
        filterControl: true,
        rowStyle: rowStyle,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'id',
                title: "{{ trans['ID'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'prenom',
                title: "{{ trans['Prénom'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'nom',
                title: "{{ trans['Nom'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'residence',
                title: "{{ trans['localité'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'adresse',
                title: "{{ trans['Adresse'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'date_naissance',
                title: "{{ trans['Naissance'] }}",
                sortable: true,
            },
            {
                field: 'telephone',
                title: "{{ trans['Téléphone'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'profession',
                title: "{{ trans['Proféssion'] }}",
                sortable: true,
                filterControl: "input"
            },
        /*{% if (userId == 1) OR in_array("venteticket_w", userPermissions) OR in_array("caisse_a", userPermissions) %}
            {
                title: "Ticket",
                align: "center",
                formatter: ticketFormatter,
            },
        {% endif %}
        {% if (userId == 1) OR in_array("ventemedic_w", userPermissions) OR in_array("ph_a", userPermissions) %}
            {
                title: "Pharmacie",
                align: "center",
                formatter: pharmacieFormatter,
            },
        {% endif %}*/
            {
                title: "Actions",
                align: "center",
                formatter: actionsFormatter,
            }
        ]
    });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>