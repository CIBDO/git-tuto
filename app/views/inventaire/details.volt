<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Details Inventaire"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Inventaire"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Details'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
         <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row" style="margin-top : 10px">
                            <div class="col-md-3">
                                <b>Réference de l'inventaire:</b> <span>{{ inventaire['id'] }}</span><br>
                                <b>objet:</b> <span>{{ inventaire['objet'] }}</span><br>
                                <b>Date:</b> <span>{{ inventaire['date'] }}</span><br>
                            </div>
                            <div class="col-md-3">
                                <b>Début:</b> <span>{{ inventaire['debut'] }}</span><br>
                                <b>Fin:</b> <span>{{ inventaire['fin'] }}</span><br>
                                <b>Etat:</b> <span class="label label-success">{{ inventaire['etat'] }}</span><br>
                            </div>
                            <div class="col-md-3">
                            {% if inventaire['etat'] != 'cloture' %}
                                <a class="editPopup btn btn-default" title="" href="#" data-toggle="modal" data-target="#editInventaire" data-inventaireid="{{ inventaire['id'] }}">Modifier</a>
                            
                                <a class="btn btn-warning clotureInventaire pull-right" href="#" title="{{trans['Cloturer']}}" data-inventaireid="{{ inventaire['id'] }}">
                                    {{trans['Cloturer']}}
                                 </a>
                            {% else %}
                                <a href="{{ url('print/detailsInventaire/') ~ inventaire['id'] }}" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                            {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{% if inventaire['etat'] != 'cloture' %}
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Ajouter des produits à l'inventaire</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('inventaire/detailsAjout') }}" class="form" method="post">
                            <div class="recherche">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <input type="hidden"  name="inventaire_id" value="{{ inventaire['id'] }}" /> 
                                        <input type="hidden"  name="s_idproduit" id="s_idproduit" value="" /> 
                                        <input type="text"  name="s_produit" id="s_produit" class="form-control typeahead" autocomplete="off" value="" placeholder="Rechercher un produit" />
                                    </div>
                                </div>
                            </div>
                            <br />

                            <div id="s_resultat">
                                
                            </div>

                            
                            <div style="margin:10px">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
{% endif %}
        
        <!-- Main row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Détails d'inventaire</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('inventaire/details') }}" class="form" method="post">
                            <div class="table-responsive">
                                <input type="hidden"  name="inventaire_id" value="{{ inventaire['id'] }}" /> 
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Initial</th>
                                            <th>Entré</th>
                                            <th>Sortie</th>
                                            <th>Théorique</th>
                                            <th>Physique</th>
                                            <th>Perte</th>
                                            <th>Ajout</th>
                                            <th>Observation</th>
                                        {% if inventaire['etat'] != 'cloture' %}
                                            <th>Action</th>
                                        {% endif %}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    {% for index, inventaireDetail in inventaireDetails %}

                                        <tr id="tr_{{ index }}">
                                            <td>
                                                <a href="#">{{ inventaireDetail['produit_libelle'] }}</a>
                                            </td>
                                            <td>
                                                {{ inventaireDetail['initial'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['entre'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['sortie'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['theorique'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['physique'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['perte'] }}
                                            </td>
                                            <td>
                                                {{ inventaireDetail['ajout'] }}
                                            </td>
                                             <td>
                                                {{ inventaireDetail['observation'] }}
                                            </td>
                                        {% if inventaire['etat'] != 'cloture' %}
                                            <td>
                                                <a class="deleteBtn" title="{{ trans["delete item"] }}" href="#" data-itemid="{{ inventaireDetail['id'] }}" data-produitname="{{ inventaireDetail['produit_libelle'] }}"><i class="glyphicon glyphicon-remove"></i></a>
                                            </td>
                                        {% endif %}
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div id="editInventaire" class="modal fade" role="dialog"></div>
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
        return '<a class="deleteBtn" title="{{ trans["delete produit"] }}" href="#" data-itemid="' + value + '" data-produitname="' + row.produit_libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
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

        function displayResult(item) {
            var current = JSON.parse(item.value);
            console.log(current);
            $("#s_idproduit").val(current.id);

            if($("#s_idproduit").val() == ""){
                return;
            }
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('inventaire/searchProduitStock')}}/" + $("#s_idproduit").val() + "/{{ inventaire['debut_times'] }}/{{ inventaire['fin_times'] }}/{{ inventaire['id'] }}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#s_resultat').html(html);
            });
        }

        function autoloader(){
            $('input.typeahead').typeahead({
                ajax: {
                    url: '{{url("inventaire/ajaxProduit")}}{% if commande is defined %}/{{ commande["id"] }}{% endif %}',
                    method: 'get',
                },
                displayField: 'libelle',
                //scrollBar:true,
                onSelect: displayResult
            });
        }
        autoloader();

        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('inventaire/editInventaire')}}/" + $(this).data('inventaireid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editInventaire').html(html);
            });
        });

        $('body').on('click', '.clotureInventaire', function () {
            
            var inventaireid = $(this).data('inventaireid');
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans["Cette action validera l'inventaire"] }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ trans["Oui, valider!"] }}',
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
                        url: "{{url('inventaire/cloture')}}/" + inventaireid,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Validé!"] }}',
                                "{{ trans["L'inventaire a été validé avec succès."] }}",
                                'success'
                            );
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

        $('body').on('click', '.deleteBtn', function () {
            var itemid = $(this).data('itemid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera le produit nommé:'] }} " + $(this).data('produitname') + " {{ trans["de l'inventaire"] }} ",
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
                        url: "{{url('inventaire/deleteDetailsItemInventaire')}}/" + itemid,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                "{{ trans["le produit a été supprimé de l'inventaire."] }}",
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
        data: [],
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "asc",
        sortName: "produit_libelle",
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
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'produit_libelle',
                title: "{{ trans['Produit'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'initial',
                title: "{{ trans['Initial'] }}",
                sortable: true,
            },
            {
                field: 'entre',
                title: "{{ trans['Entre'] }}",
                sortable: true,
            },
            {
                field: 'sortie',
                title: "{{ trans['Sortie'] }}",
                sortable: true,
            },
            {
                field: 'theorique',
                title: "{{ trans['Theorique'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'physique',
                title: "{{ trans['Physique'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'perte',
                title: "{{ trans['Perte'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'ajout',
                title: "{{ trans['Ajout'] }}",
                sortable: true,
            },
            {
                field: 'observation',
                title: "{{ trans['Observation'] }}",
                sortable: true,
                filterControl: "select"
            },
        {% if inventaire['etat'] != 'cloture' %}
            {
                field: 'id',
                title: "Actions",
                align: "center",
                formatter: actionsFormatter,
            }
        {% endif %}
        ]
    });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();
    });
</script>