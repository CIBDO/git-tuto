<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Produits"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Produits"]}}</a></li>
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

                            <div class="row">
                                <div class="form-group  col-md-5" style="margin-right : 10px">
                                    <label for="etat">{{ trans['Filtre']}} :</label>
                                    {{ select(['filtre',  ['tous' : 'Tous les produits', 'rupture' : 'Produits en rupture de stock', 'seuilMin' : 'Produits en dessous du seuil mininal'], 'useEmpty' : false, 'class': 'form-control', 'required' : 'required']) }}
                                </div>

                                <div class="form-group  col-md-4" style="margin-right : 10px">
                                   <!--  <label for="etat">{{ trans['Etat']}} :</label>
                                    {{ select(['etat',  ['actif' : 'Actif', 'inactif' : 'Inactif'], 'useEmpty' : false, 'class': 'form-control', 'required' : 'required']) }} -->
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

        <div class="row">
            <div class="col-xs-12" >
               {% if (userId == 1) OR in_array("ph_w", userPermissions) OR in_array("ph_a", userPermissions) %}
                <button type="button" class="btn btn-info createPopup pull-right" data-toggle="modal" data-target="#createProduit">
                    <i class="fa fa-plus"></i> {{trans['Créer un produit']}}
                </button>
                {% endif %}
            </div>
        </div>

        <!-- Main row -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">

                        <div id="toolbar" class="btn-group">
                            <button type="button" class="btn btn-default createCommande" title="{{trans['Commander']}}">
                                {{trans["Commander"]}}
                            </button>
                            <button type="button" class="btn btn-default createReception" title="{{trans['Réceptionner sans passer par une commande']}}">
                                {{trans["Réception rapide"]}}
                            </button>
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
    <div id="createReception" class="modal fade" role="dialog"></div>
    <div id="createProduit" class="modal largemodal fade" role="dialog"></div>
    <div id="editProduit" class="modal largemodal fade" role="dialog"></div>
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
        return '<a class="editPopup" title="{{ trans["Modifier Produit"] }}" href="#" data-toggle="modal" data-target="#editProduit" data-produit="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["Supprimer un Produit"] }}" href="#" data-produit="' + value + '" data-produitname="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
    }

    function libelleFormatter(value, row, index) {
        return '<a title="{{ trans["Voir tous les details de ce produit"] }}" href="{{url('produit/fiche')}}/'+row.id+'">'+value+'</a>';
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
                url: "{{url('produit/createProduit')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createProduit').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('produit/editProduit')}}/" + $(this).data('produit'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editProduit').html(html);
            });
        });

        $('body').on('click', '.createCommande', function () {
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
                url: "{{url('commande/createCommande')}}/" + txtID.join(","),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createCommande').html(html);
                $('#createCommande').modal('show');
            });
        });

        $('body').on('click', '.createReception', function () {
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
                url: "{{url('reception/createReception')}}/0/" + txtID.join(","),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createReception').html(html);
                $('#createReception').modal('show');
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var produit_id = $(this).data('produit');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Etes vous sûre?"] }}',
                    text: "{{ trans['Cette action supprimera le produit nommé:'] }} " + $(this).data('produitname'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ trans["Oui, supprimé!"] }}',
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
                            url: "{{url('produit/deleteProduit')}}/" + produit_id,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '{{ trans["Supprimé!"] }}',
                                    '{{ trans[" Le produit a été supprimé avec succès."] }}',
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
            data: {{ produits }},
            cache: false,
            striped: true,
            pagination: true,
            pageSize: 10,
            pageList: [10, 25, 50, 100, 200],
            sortOrder: "asc",
            sortName: "libelle",
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
            showColumns: true,
            filterControl: true,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'type',
                    title: "{{ trans['Type'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'libelle',
                    title: "{{ trans['Libellé'] }}",
                    sortable: true,
                    filterControl: "input",
                    formatter: libelleFormatter,
                },
                {
                    field: 'forme',
                    title: "{{ trans['Forme'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'dosage',
                    title: "{{ trans['Dosage'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'classe_th',
                    title: "{{ trans['Classe thérapeutique'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'presentation',
                    title: "{{ trans['Presentation'] }}",
                    sortable: true,
                },
                {
                    field: 'seuil_min',
                    title: "{{ trans['Seuil min'] }}",
                    sortable: true,
                },
                {
                    field: 'seuil_max',
                    title: "{{ trans['Seuil max'] }}",
                    sortable: true,
                },
                {
                    field: 'stock',
                    title: "{{ trans['Stock'] }}",
                    sortable: true,
                },
                {% if (userId == 1) OR in_array("ph_w", userPermissions) OR in_array("ph_a", userPermissions) %}
                {
                    field: 'id',
                    title: "Actions",
                    align: "center",
                    formatter: actionsFormatter,
                }
                {% endif %}
            ]
        });

        $('#table-javascript').bootstrapTable('hideColumn', 'type');
        $('#table-javascript').bootstrapTable('hideColumn', 'forme');
        $('#table-javascript').bootstrapTable('hideColumn', 'classe_th');

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>