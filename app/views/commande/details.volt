<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Détails commande"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Commandes"]}}</a></li>
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
                                <b>Référence de la commande:</b> <span>{{ commande['id'] }}</span><br>
                                <b>Objet:</b> <span>{{ commande['objet'] }}</span><br>
                                <b>Date:</b> <span>{{ commande['date'] }}</span><br>
                            </div>
                            <div class="col-md-3">
                                <b>Montant:</b> <span>{{ number_format(commande['montant'],0,'.',' ') }}</span><br>
                                <b>Etat:</b> <span class="label label-success">{{ commande['etat'] }}</span><br>
                            </div>
                            <div class="col-md-3">
                                <b>Fournisseur:</b><br>
                                <span>{{ commande['fournisseur'] }}</span>
                            </div>
                            <div class="col-md-3">
                            {% if commande['etat'] != 'cloture' %}
                                <a class="editPopup btn btn-default" title="" href="#" data-toggle="modal" data-target="#editCommande" data-commandeid="{{ commande['id'] }}">Modifier</a>
                            
                                 <a class="btn btn-primary editReception pull-right" href="#" title="{{trans['Receptionner cette commande']}}" data-toggle="modal" data-target="#editReception" data-commandeid="{{ commande['id'] }}">
                                    {{trans['Réceptionner']}}
                                 </a>
                            {% endif %}
                                <a href="{{ url('print/detailsCommande/') ~ commande['id'] }}" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

{% if commande['etat'] == 'creation' %}
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Ajouter un produit à la commande</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('commande/detailsAjout') }}" class="form" method="post">
                            <div class="table-responsive">
                                <input type="hidden"  name="commande_id" value="{{ commande['id'] }}" /> 
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr class="todo-list"> 
                                            <td colspan="4">
                                                <input type="hidden"  name="s_idproduit" id="s_idproduit" value="" /> 
                                                <input type="text"  name="s_produit" id="s_produit" class="form-control typeahead" size="35" autocomplete="off" value="" placeholder="Rechercher un produit" />
                                            </td>
                                            <td colspan="7">
                                                
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
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                      <h3 class="box-title">Détails de la commande</h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('commande/details') }}" class="form" method="post">
                            <div class="table-responsive">
                                <input type="hidden"  name="commande_id" value="{{ commande['id'] }}" /> 
                                <table class="table no-margin table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix d'achat</th>
                                            <th>Quantité livrée</th>
                                        {% if commande['etat'] == 'creation' %}
                                            <th>Action</th>
                                        {% endif %}
                                        </tr>
                                    </thead>
                                    <tbody id="myBody">
                                    {% for index, commandeDetail in commandeDetails %}

                                        <tr>
                                            <td>
                                                <input type="hidden"  name="id[]" value="{{ commandeDetail['id'] }}" /> 
                                                <input type="hidden"  name="produit_id[]" value="{{ commandeDetail['produit_id'] }}" /> 
                                                <a href="#">{{ commandeDetail['produit_libelle'] }}</a>
                                            </td>
                                            <td>
                                                <input type="number"  min="0" style="width: 70px;" {% if commande['etat'] != 'creation' %} readonly="readonly" {% endif %} name="quantite[]" autocomplete="off" value="{{ commandeDetail['quantite'] }}" placeholder="Quantité" />
                                            </td>
                                            <td>
                                                <input type="number" min="0" name="prix[]" style="width: 70px;" {% if commande['etat'] != 'creation' %} readonly="readonly" {% endif %} autocomplete="off" value="{{ commandeDetail['prix'] }}" placeholder="Prix" />
                                            </td>
                                            <td>
                                                <input type="number" name="quantite_livree[]" style="width: 70px;" autocomplete="off" value="{{ commandeDetail['quantite_livree'] }}" disabled="disabled" placeholder="Quantité livrée" />
                                            </td>
                                        {% if commande['etat'] == 'creation' %}
                                            <td>
                                                <a class="deleteBtn" title="{{ trans["delete item"] }}" href="#" data-itemid="{{ commandeDetail['id'] }}" data-produitname="{{ commandeDetail['produit_libelle'] }}"><i class="glyphicon glyphicon-remove"></i></a>
                                            </td>
                                        {% endif %}
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            
                            </div>
                        {% if commande['etat'] == 'creation' %}
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
    

    function actionsFormatter(value, row, index) {
        return '<a class="deleteBtn" title="{{ trans["delete prduit"] }}" href="#" data-itemid="' + value + '" data-produitname="' + row.produit_libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
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
            //console.log(current);
            var reste = current.seuil_max;
            if ($('#tr_'+current.id).length > 0) {
                swal('{{ trans["Le produit est déja dans liste!"] }}', '', 'info');
                return;
            }
            var text = '<tr id="tr_'+current.id+'">' +
                            '<td>' +
                                '<input type="hidden"  name="id[]" value="'+ current.id +'" /> ' +
                                '<input type="hidden"  name="produit_id[]" value="'+ current.id +'" /> ' +
                                '<a href="#">'+ current.libelle +'-'+ current.dosage +'-'+ current.forme_libelle +'</a>' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="1" style="width: 70px;" max="'+ reste +'" name="quantite[]" autocomplete="off" value="" required="required" placeholder="Quantité" />' +
                            '</td>' +
                            '<td>' +
                                '<input type="number" min="0" style="width: 70px;" name="prix[]" autocomplete="off" value="" placeholder="Prix d\'achat" />' +
                            '</td>' +                            
                            '<td>' +
                                '<input type="number" name="quantite_livree[]" style="width: 70px;" autocomplete="off" value="0" disabled="disabled" placeholder="Quantité livrée" />' +
                            '</td>' +
                            '<td>' +
                                '' +
                            '</td>' +
                        '</tr>';

            $("#myBody").prepend(text);
            setTimeout(function(){ $('#s_produit').val(''); }, 1000);
            $('#tr_'+current.id).css("background-color", "#ccc");

        }

        function autoloader(){
            $('input.typeahead').typeahead({
                ajax: {
                    url: '{{url("commande/ajaxProduit")}}/{{ commande["id"] }}',
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
                url: "{{url('commande/editCommande')}}/" + $(this).data('commandeid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editCommande').html(html);
            });
        });

        $('body').on('click', '.editReception', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('reception/createReception')}}/" + $(this).data('commandeid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editReception').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var itemid = $(this).data('itemid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera le produit nommé:'] }} " + $(this).data('produitname') + " {{ trans['de la commande'] }} ",
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
                        url: "{{url('commande/deleteDetailsItemCommande')}}/" + itemid,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["Le produit a été supprimé de la commande avec succès."] }}',
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
        // Function located in opsise.js for modal form processing
        submitAjaxForm();
    });
</script>