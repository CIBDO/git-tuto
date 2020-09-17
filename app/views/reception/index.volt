<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Réceptions"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Réceptions"]}}</a></li>
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
                                    <button type="submit" class="btn btn-defaultx    pull-right" title="{{trans['Recherche']}}">
                                        <i class="fa fa-fw fa-filter"></i> {{trans['Filtrer']}}
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12" >
               
                <button type="button" class="btn btn-info createPopup pull-right"  data-toggle="modal" data-target="#createReception">
                    <i class="fa fa-plus"></i> {{trans['Créer une réception']}}
                </button>
                               
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

    <div id="createReception" class="modal fade" role="dialog"></div>
    <div id="editReception" class="modal fade" role="dialog"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }
    
    function detailsFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs col-xs-12 " href="{{ url("reception/details/") }}'+row.id+'"><i class="fa fa-search pull-left"></i>Details</a>';
    }

    function commandeFormatter(value, row, index) {
        if(row.commande_id != ""){
            //return '<a class="btn btn-info btn-xs col-xs-12 " href=\'{{ url("commande/details/") }}'+row.commande_id+'\'"><i class="fa fa-search pull-left">Details</a>';
            return '<a class="btn btn-info btn-xs " href=\'{{ url("commande/details/") }}'+row.commande_id+'\'"><i class="fa fa-search pull-left"></i>Details</a>';
        }
        else{
            return '';
        }
    }

    function actionsFormatter(value, row, index) {
        if(row.etat == "encours"){
            return '<a class="editPopup" title="{{ trans["edit Reception"] }}" href="#" data-toggle="modal" data-target="#editReception" data-receptionid="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["delete Reception"] }}" href="#" data-receptionid="' + value + '" data-receptionname="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
        }
        else{
            return '';
        }
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

        $('body').on('click', '.createPopup', function () {
            $.ajax({
                url: "{{url('reception/createReception')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createReception').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('reception/editReception')}}/" + $(this).data('receptionid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editReception').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var reception_id = $(this).data('receptionid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera la réception'] }} ",
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
                })
                .then(function() {
                    $('body').addClass('loading');
                    $.ajax({
                        url: "{{url('reception/deleteReception')}}/" + reception_id,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["la réception a été supprimée avec succès."] }}',
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
                }, function(dismiss) {
                  // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                  // if (dismiss === 'cancel') {
                  //   swal(
                  //     'Cancelled',
                  //     '---',
                  //     'warning'
                  //   );
                  // }
                });
        });

        $('#table-javascript').bootstrapTable({
        data: {{ receptions }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "date",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
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
                field: 'objet',
                title: "{{ trans['Objet'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'fournisseur',
                title: "{{ trans['Fournisseur'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'date',
                title: "{{ trans['Date'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'etat',
                title: "{{ trans['Etat'] }}",
                sortable: true,
                filterControl: "select",
                formatter: function(value){
                    if(value == "cloture")
                        return 'Clôturé';
                    if(value == "encours")
                        return 'Encours';
                }
            },
            {
                field: 'commande_objet',
                title: "{{ trans['Commande'] }}",
                sortable: true,
                filterControl: "input",
                formatter: commandeFormatter
            },
            {
                title: "{{ trans['Details'] }}",
                align: "center",
                formatter: detailsFormatter,
            },
            {
                field: 'id',
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