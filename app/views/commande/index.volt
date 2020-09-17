<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Commandes"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Commandes"]}}</a></li>
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
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-right" title="{{trans['Recherche']}}">
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

    <div id="createCommande" class="modal fade" role="dialog"></div>
    <div id="editCommande" class="modal fade" role="dialog"></div>
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
        return '<a class="btn btn-primary btn-xs col-xs-12 " href="{{ url("commande/details/") }}'+row.id+'"><i class="fa fa-search pull-left"></i>Details</a>';
    }

    function actionsFormatter(value, row, index) {
        if(row.etat == "creation"){
            return '<a class="editPopup" title="{{ trans["edit Commande"] }}" href="#" data-toggle="modal" data-target="#editCommande" data-commandeid="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                    '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["delete Commande"] }}" href="#" data-commandeid="' + value + '" data-commandename="' + row.objet + '"><i class="glyphicon glyphicon-remove"></i></a>';
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

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        $('body').on('click', '.createPopup', function () {
            $.ajax({
                url: "{{url('commande/createCommande')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createCommande').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('commande/editCommande')}}/" + $(this).data('commandeid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editCommande').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var commande_id = $(this).data('commandeid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Etes vous sure?"] }}',
                    text: "{{ trans['Cette action supprimera la commande'] }} ",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ trans["Oui, supprimer!"] }}',
                    cancelButtonText: '{{ trans["Non, annuler!"] }}',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: false,
                    closeOnCancel: true
                })
                .then(function() {
                        $('body').addClass('loading');
                        $.ajax({
                            url: "{{url('commande/deleteCommande')}}/" + commande_id,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            console.log(result);
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '{{ trans["Supprimée!"] }}',
                                    '{{ trans["La commande à été supprimée avec succès."] }}',
                                    'success'
                                );
                                $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                                $('body').addClass('loading');
                                window.location.reload();
                            }
                            else{
                                swal(
                                    '{{ trans["Annulée!"] }}',
                                    "{{ trans['Annulée'] }}",
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
        data: {{ commandes }},
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
        //showLoading: true,
        showExport: true,
        showMultiSort: true,
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
                field: 'montant',
                title: "{{ trans['Montant'] }}",
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
                    if(value == "creation")
                        return 'Création';
                    if(value == "reception")
                        return 'Réception';
                }
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