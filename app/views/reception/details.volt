<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Détails Réception"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Réception"]}}</a></li>
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
                                <b>Référence de la réception:</b> <span>{{ reception['id'] }}</span><br>
                                <b>Objet:</b> <span>{{ reception['objet'] }}</span><br>
                                <b>Date:</b> <span>{{ reception['date'] }}</span><br>
                                <b>Etat:</b> <span class="label label-success">{{ reception['etat'] }}</span><br>
                            </div>
                            {% if commande is defined %}
                            <div class="col-md-3">
                                <b>Référence de la commande:</b> <span>{{ commande['id'] }}</span><br>
                                <b>Objet:</b> <span>{{ commande['objet'] }}</span><br>
                                <b>Date:</b> <span>{{ commande['date'] }}</span><br>
                                <b>Montant:</b> <span>{{ commande['montant'] }}</span><br>
                                <b>Etat:</b> <span class="label label-success">{{ commande['etat'] }}</span><br>
                            </div>
                            {% endif %}
                            <div class="col-md-3">
                                <b>Fournisseur:</b><br>
                                <span>{{ reception['fournisseur'] }}</span>
                            </div>
                            <div class="col-md-3">
                            
                            {% if reception['etat'] != 'cloture' %}

                                <a class="editPopup  btn btn-default" title="" href="#" data-toggle="modal" data-target="#editReception" data-receptionid="{{ reception['id'] }}">Modifier</i></a>
                            
                                <a class="btn btn-warning clotureReception pull-right" href="#" title="{{trans['Cloturer']}}" data-toggle="modal" data-target="#clotureReception" data-receptionobjet="{{ reception['objet'] }}" data-receptionid="{{ reception['id'] }}">
                                    {{trans['Clôturer']}}
                                 </a>
                            {% else %}
                                <a href="{{ url('print/detailsReception/') ~ reception['id'] }}" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                            {% endif %}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{% if reception['etat'] != 'cloture' %}
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">
                
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Ajouter des produits à la réception</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('reception/detailsAjout') }}" class="form" method="post">
                            <div class="table-responsive">
                                {% if commande is defined %}
                                <input type="hidden"  name="commande_id" value="{{ commande['id'] }}" />
                                {% endif %}
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr class="todo-list"> 
                                            <td colspan="11">
                                                <input type="hidden"  name="s_idproduit" id="s_idproduit" value="" /> 
                                                <input type="text"  name="s_produit" id="s_produit" class="form-control typeahead" size="35" autocomplete="off" value="" placeholder="Rechercher un produit" />
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            
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
                      <h3 class="box-title">Détails de la réception</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('reception/details') }}" class="form" method="post">
                            <div class="table-responsive">
                                <input type="hidden"  name="reception_id" value="{{ reception['id'] }}" /> 
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Litige</th>
                                            <th>Manquant</th>
                                            <th>Observation</th>
                                            <th>Lot</th>
                                            <th>Date péremption</th>
                                            <th>Pr Achat</th>
                                            <th>Coef</th>
                                            <th>Pr de Vente</th>
                                        {% if reception['etat'] != 'cloture' %}
                                            <th>Action</th>
                                        {% endif %}
                                        </tr>
                                    </thead>
                                    <tbody id="myBody">
                                    {% for index, receptionDetail in receptionDetails %}

                                        <tr id="tr_{{ receptionDetail['produit_id'] }}" data-index="{{ receptionDetail['produit_id'] }}">
                                            <td>
                                                <input type="hidden"  name="id[]" value="{{ receptionDetail['id'] }}" /> 
                                                <input type="hidden"  name="produit_id[]" value="{{ receptionDetail['produit_id'] }}" /> 
                                                <a href="#">{{ receptionDetail['produit_libelle'] }}</a>
                                            </td>
                                            <td>
                                                <input type="number"  style="width: 70px;" min="0" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} name="quantite[]" autocomplete="off" max="{{ receptionDetail['reste_a_livree'] }}" value="{{ receptionDetail['quantite'] }}" placeholder="Quantité" />
                                            </td>
                                            <td>
                                                <input type="number" style="width: 70px;" min="0" name="litige[]" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} autocomplete="off" value="{{ receptionDetail['litige'] }}" placeholder="litige" />
                                            </td>
                                            <td>
                                                <input type="number" style="width: 70px;" min="0" name="manquant[]" autocomplete="off" value="{{ receptionDetail['manquant'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="Manquant" />
                                            </td>
                                            <td>
                                                <input type="text" style="width: 140px;" name="observation[]" autocomplete="off" value="{{ receptionDetail['observation'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="observation" />
                                            </td>
                                            <td>
                                                <input type="text" style="width: 110px;" name="lot[]" autocomplete="off" value="{{ receptionDetail['lot'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="Lot" />
                                            </td>
                                            <td>
                                                <input type="date" style="width: 140px;" name="date_peremption[]" autocomplete="off"  value="{{ receptionDetail['date_peremption'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="Date peremption" />
                                            </td>
                                            <td>
                                                <input type="number" style="width: 70px;" min="0" id="prix_achat_{{ receptionDetail['produit_id'] }}" class="prix_achat" name="prix_achat[]" autocomplete="off" value="{{ receptionDetail['prix_achat'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="Prix achat" />
                                            </td>
                                             <td>
                                                <input type="text" style="width: 70px;" id="coef_{{ receptionDetail['produit_id'] }}" class="coef" name="coef[]" autocomplete="off" value="{{ receptionDetail['coef'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="coef" />
                                            </td>
                                             <td>
                                                <input type="number" style="width: 70px;" id="prix_vente_{{ receptionDetail['produit_id'] }}" min="0" name="prix_vente[]" autocomplete="off" value="{{ receptionDetail['prix_vente'] }}" {% if reception['etat'] == 'cloture' %} readonly="readonly" {% endif %} placeholder="Prix vente" />
                                            </td>
                                        {% if reception['etat'] != 'cloture' %}
                                            <td>
                                                <a class="deleteBtn" title="{{ trans["delete item"] }}" href="#" data-itemid="{{ receptionDetail['id'] }}" data-produitname="{{ receptionDetail['produit_libelle'] }}"><i class="glyphicon glyphicon-remove"></i></a>
                                            </td>
                                        {% endif %}
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            
                            </div>
                        {% if reception['etat'] != 'cloture' %}
                            <div style="margin:10px">
                                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
                            </div>
                        {% endif %}
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div id="editReception" class="modal fade" role="dialog"></div>
    <div id="clotureReception" class="modal fade" role="dialog"></div>
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

        function calculPrixVente(_coef, _prix_achat) {
            var coef        = _coef;
            var prix_achat  = _prix_achat;
            var rs = coef * prix_achat;

            chTemp=Math.round(rs);
            if((chTemp % 5) == 0){
                return chTemp;
            }
            
            var chFin; var chDebut;
            chDebut     = String(chTemp).substr(0, String(chTemp).length -1);
            chFin       = String(chTemp).substr(String(chTemp).length -1, 1);

            if(Number(chFin) < 5){
                do{ chTemp--; } while((chTemp % 5) != 0);
            }
            if(Number(chFin) > 5){
                do{ chTemp++; } while((chTemp % 5) != 0);
            }
            return chTemp;
        }
        $('body').on('change', '#coef', function() { 
            var coef        = isNaN(parseFloat($("#coef").val())) ? 0 : parseFloat($("#coef").val());
            var prix_achat  = isNaN(parseFloat($("#prix_achat").val())) ? 0 : parseFloat($("#prix_achat").val());
            var rs = calculPrixVente(coef, prix_achat); 
            $("#prix_vente").val(rs); 
        });
        $('body').on('change', '#prix_achat', function() { 
            var coef        = isNaN(parseFloat($("#coef").val())) ? 0 : parseFloat($("#coef").val());
            var prix_achat  = isNaN(parseFloat($("#prix_achat").val())) ? 0 : parseFloat($("#prix_achat").val());
            var rs = calculPrixVente(coef, prix_achat); 
            $("#prix_vente").val(rs);
        });

        function _initCalculPrixVente(){
            $(".coef").each(function(){
                var index = $(this).closest("tr").data("index");
                $('#coef_' + index).change(function() {
                    var coef = isNaN(parseFloat($('#coef_' + index).val())) ? 0 : parseFloat($('#coef_' + index).val());
                    var prix_achat  = isNaN(parseFloat($('#prix_achat_' + index).val())) ? 0 : parseFloat($('#prix_achat_' + index).val());
                    var rs = calculPrixVente(coef, prix_achat); 
                    $("#prix_vente_" + index).val(rs);
                });
                $('#prix_achat_' + index).change(function() {
                    var coef = isNaN(parseFloat($('#coef_' + index).val())) ? 0 : parseFloat($('#coef_' + index).val());
                    var prix_achat  = isNaN(parseFloat($('#prix_achat_' + index).val())) ? 0 : parseFloat($('#prix_achat_' + index).val());
                    var rs = calculPrixVente(coef, prix_achat); 
                    $("#prix_vente_" + index).val(rs);
                });
            }); 
        }
        _initCalculPrixVente();

        //Pour le premier calcule apres chargement de la page
        $(".coef").each(function(){
            var index = $(this).closest("tr").data("index");
            var coef = isNaN(parseFloat($('#coef_' + index).val())) ? 0 : parseFloat($('#coef_' + index).val());
            var prix_achat  = isNaN(parseFloat($('#prix_achat_' + index).val())) ? 0 : parseFloat($('#prix_achat_' + index).val());
            var rs = calculPrixVente(coef, prix_achat); 
            $("#prix_vente_" + index).val(rs);
        });
        

        function displayResult(item) {

            var current = JSON.parse(item.value);
            
            var lot_multiple = {{lot_multiple}};
            if(lot_multiple == 0){
                if ($('#tr_'+current.id).length > 0) {
                    swal('{{ trans["Le produit est déja dans liste!"] }}', '', 'warning');
                    return;
                }
            }
            //console.log(current);
            var reste = current.seuil_max;
            var _prix_achat = "";
            var text = '<tr id="tr_'+current.id+'" data-index="'+current.id+'">' +
                            '<td>' +
                                '<input type="hidden"  name="id[]" value="{{ reception["id"] }}" /> ' +
                                '<input type="hidden"  name="reception_id" value="{{ reception["id"] }}" /> ' +
                                '<input type="hidden"  name="produit_id[]" value="'+ current.id +'" /> ' +
                                '<a href="#">'+ current.libelle +'-'+ current.dosage +'-'+ current.forme_libelle +'</a>';
                if(current.quantite != null && current.quantite != ""){
                    text +=  "<br>Déjà Livrée:" +  current.quantite_livree + '/' + current.quantite;
                    reste = current.quantite - current.quantite_livree;
                    _prix_achat = current.prix_achat;
                } 
                    text += '</td>' +
                            '<td>' +
                                '<input type="number" min="1" style="width: 70px;" max="'+ reste +'" name="quantite[]" autocomplete="off" value="" required="required" placeholder="Quantité" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="0" style="width: 70px;" name="litige[]" autocomplete="off" value="" placeholder="Litige" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="0" style="width: 70px;" name="manquant[]" autocomplete="off" value="" placeholder="Manquant" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="text" name="observation[]" style="width: 140px;" autocomplete="off" value="" placeholder="Observation" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="text" style="width: 110px;" name="lot[]" required="required" autocomplete="off" value="' + current.def_lot + '" placeholder="Lot" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="date" style="width: 140px;" style="width: 70px;" required="required" name="date_peremption[]" autocomplete="off" value="' + current.def_peremption + '" placeholder="Date de peremption" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="0" style="width: 70px;" name="prix_achat[]" id="prix_achat_'+current.id+'" autocomplete="off" value="'+ _prix_achat +'" placeholder="Pr Achat" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="text" min="0" style="width: 70px;" name="coef[]" id="coef_'+current.id+'" class="coef" autocomplete="off" value="' + current.def_coef + '" placeholder="Coef" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="0" style="width: 70px;" name="prix_vente[]" id="prix_vente_'+current.id+'" autocomplete="off" value="" placeholder="Pr vente" />' +
                            '</td>' +
                            '<td>' +
                                '<a class="deleteBtn2"><i class="glyphicon glyphicon-remove"></i></a>' +
                            '</td>' +
                        '</tr>';

            $("#myBody").prepend(text);
            $('#tr_'+current.id).css("background-color", "#ccc");
            setTimeout(function(){ $('#s_produit').val(''); }, 1000);
            _initCalculPrixVente();
        }

        function autoloader(){
            $('input.typeahead').typeahead({
                ajax: {
                    url: '{{url("reception/ajaxProduit")}}/{{reception["id"]}}{% if commande is defined %}/{{ commande["id"] }}{% endif %}',
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
                url: "{{url('reception/editReception')}}/" + $(this).data('receptionid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editReception').html(html);
            });
        });

        $('body').on('click', '.clotureReception', function () {
            
            var receptionid = $(this).data('receptionid');
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('reception/cloture')}}/" + receptionid,
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#clotureReception').html(html);
            });
        });

        $('body').on('click', '.deleteBtn2', function () {
            var currentTr = $(this).closest("tr");
            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
        });

        $('body').on('click', '.deleteBtn', function () {
            var itemid = $(this).data('itemid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera le produit nommé:'] }} " + $(this).data('produitname') + " {{ trans['de la réception'] }} ",
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
                        url: "{{url('reception/deleteDetailsItemReception')}}/" + itemid,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["Le produit a été supprimé de la réception."] }}',
                                'success'
                            );
                            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                            /*$('body').addClass('loading');
                            window.location.reload();*/
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
                field: 'quantite',
                title: "{{ trans['Quantité'] }}",
                sortable: true,
            },
            {
                field: 'litige',
                title: "{{ trans['Litige'] }}",
                sortable: true,
            },
            {
                field: 'manquant',
                title: "{{ trans['Manquant'] }}",
                sortable: true,
            },
            {
                field: 'observation',
                title: "{{ trans['Observation'] }}",
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
                title: "{{ trans['Date peremption'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'prix_achat',
                title: "{{ trans['Pr Achat'] }}",
                sortable: true,
            },
            {
                field: 'coef',
                title: "{{ trans['Coef'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'prix_vente',
                title: "{{ trans['Pr Vente'] }}",
                sortable: true,
            },
        {% if reception['etat'] != 'cloture' %}
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