<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Caisse de vente de produit'] }} - <b>{{ pointDeVente['libelle'] }}</b>
            <button type="button" class="btn btn-info pull-right hidden openOrdonnanceList" data-toggle="modal" data-target="#openOrdonnanceList">
                {{trans['Ordonnances']}}
            </button>
        </h1>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
         <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline form-caisse" role="form" action="" method="post">
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group col-md-12">
                                        <input type="text" style="width: 100%" name="s_patient" id="s_patient" class="form-control typeahead_patient" autocomplete="off" value="" placeholder="Recherche mutilicritaire" />
                                    </div>
                                        
                                </div>
                            </div> 

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4" style="margin-right : 10px">
                                        <label for="Services" style="width: 100px">{{ trans['Identifiant']}} :</label>
                                        {{ textField(['id', 'class': 'form-control', 'readonly' : 'readonly']) }}
                                    </div>
                                    <div class="form-group  col-md-4" style="margin-right : 10px">

                                    </div>
                                    <div class="form-group  col-md-4 pull-right">
                                        <input type="hidden" name="pointDeVente_id" id="pointDeVente_id" class="form-control" value="{{ pointDeVente['id'] }}" />
                                        
                                    </div>
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                       <label for="prenom" style="width: 100px">{{ trans['Prénom']}} :</label>
                                        {{ textField(['prenom', 'class': 'form-control', 'required' : 'required']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="nom" style="width: 100px">{{ trans['Nom']}} :</label>
                                        {{ textField(['nom', 'class': 'form-control', 'required' : 'required']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="date_naissance" style="width: 100px">{{ trans['Naissance']}} :</label>
                                        {{ dateField(['date_naissance', 'class': 'form-control', 'id': 'date_naissance', 'required' : 'required']) }}
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="telephone" style="width: 100px">{{ trans['Téléphone']}} :</label>
                                        {{ textField(['telephone', 'class': 'form-control', 'data-inputmask' : '"mask": "99-99-99-99"', 'data-mask' : '']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="residence_id" style="width: 100px">{{ trans['Résidence']}} :</label>
                                        {{ selectStatic(['residence_id', residences, 'using' : ['id', 'libelle'], 'class': 'form-control', 'id': 'residence_id', 'useEmpty': true]) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="adresse" style="width: 100px">{{ trans['Adresse']}} :</label>
                                        {{ textField(['adresse', 'class': 'form-control']) }}
                                    </div>
                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="sexe" style="width: 100px">{{ trans['Sexe']}} :</label>
                                        {{ select('sexe',  ['m' : 'Masculin', 'f' : 'Feminin'], 'useEmpty' : true, 'class': 'form-control', 'id' : '_sexe', 'required' : 'required') }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="num_ordonnance" style="width: 100px">{{ trans['Ordonnance']}} :</label>
                                        {{ textField(['num_ordonnance', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="typeAssurance" style="width: 100px">{{ trans["Prise en charge"]}} :</label>
                                        <input type="hidden"  name="type_assurance_id" id="type_assurance_id" value="" /> 
                                        {{ select('type_assurance', typeAssurancelist, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : '_type_assurance') }}
                                    </div>
                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px" id="infos_assure">
                                <div class="col-md-12" >

                                    <div class="form-group  col-md-2">
                                        <label for="numero_assurance" style="width: 100px">{{ trans['Numéro']}} :</label><br>
                                        {{ textField(['numero_assurance', 'id' : '_numero_assurance', 'class': 'form-control', 'placeholder' : 'numero_assurance']) }}
                                    </div>

                                    <div class="form-group  col-md-2">
                                        <label for="ogd" style="width: 100px">{{ trans['OGD']}} :</label><br>
                                        {{ select('ogd', ['inps' : 'inps', 'cmss' : 'cmss'], 'class': 'form-control', 'id' : '_ogd', 'useEmpty' : true) }}
                                    </div>
                                    <div class="form-group  col-md-2">
                                        <label for="beneficiaire" style="width: 100px">{{ trans['Bénéficiaire']}} :</label><br>
                                        {{ select('beneficiaire', ['titulaire' : 'titulaire', 'enfant' : 'enfant', 'parent' : 'parent'], 'class': 'form-control', 'id' : '_beneficiaire', 'useEmpty' : true) }}
                                    </div>
                                    <div class="form-group  col-md-2">
                                        <label for="autres_infos" style="width: 100px">{{ trans['Autres infos']}} :</label><br>
                                        {{ textField(['autres_infos', 'id' : '_autres_infos', 'class': 'form-control', 'placeholder' : 'Autres infos']) }}
                                    </div>

                                    <div class="form-group  col-md-2">
                                        <label for="type_assurance_taux" style="width: 100px">{{ trans['Taux']}} :</label><br>
                                        {{ numericField(['type_assurance_taux', 'id' : '_type_assurance_taux', 'class': 'form-control', 'readonly' : 'readonly', 'placeholder' : 'Taux']) }}
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-xs-12" >
                                    <div class="box box-default" style="width: 100%;padding: 0;margin: 0;">
                                      <div class="box-body">
                                        <table class="table table-hover">
                                            <thead>
                                                <th> Produit </th>
                                                <th> Prix </th>
                                                <th> Quantité </th>
                                                <th> Total </th>
                                            </thead>
                                            <tbody id="produitList2">
                                                <tr class="todo-list"> 
                                                    <td>
                                                        <input type="hidden"  name="s_idproduit" id="s_idproduit" value="" /> 
                                                        <input type="text" style="width: 100%;" name="s_produit" id="s_produit" class="form-control typeahead" size="35" autocomplete="off" value="" placeholder="Produit" />
                                                    </td>
                                                    <td> 
                                                        <input type="number" style="width: 110px;" name="s_prix" id="s_prix" min="0" class="form-control" autocomplete="off" value="" readonly="readonly" placeholder="Prix" />
                                                        <input type="hidden" style="width: 110px;" name="s_reste" id="s_reste" class="form-control" autocomplete="off" value="" readonly="readonly" placeholder="Prix" />
                                                    </td>
                                                    <td> 
                                                        <input type="number"  style="width: 70px;" name="s_quantite" id="s_quantite" min="1" class="form-control" autocomplete="off" value="" placeholder="Quantité" />
                                                    </td>
                                                    <td>
                                                        <input type="hidden"  name="s_prix" id="s_prix" value="" /> 
                                                        <input type="number" style="width: 110px;" name="s_montant_total_produit" id="s_montant_total_produit" min="0" class="form-control" autocomplete="off" value="" readonly="readonly" placeholder="total" />
                                                    </td>
                                                    <td>
                                                        <a style="cursor: pointer;" class="btn btn-default produitAdd" title="Ajouter">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                          </table>
                                      </div>
                                    </div>
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="total" style="width: 150px">{{ trans['Total'] }} :</label>
                                        {{ numericField(['total', 'class': 'form-control', 'id' : 'total', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="total_reste" style="width: 150px">{{ trans['Prise en charge'] }} :</label>
                                        {{ numericField(['total_reste', 'class': 'form-control', 'id' : 'total_reste', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="total_a_payer" style="width: 150px">{{ trans['Total à payer'] }} :</label>
                                        {{ numericField(['total_a_payer', 'class': 'form-control', 'id' : 'total_a_payer', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="montant_recu" style="width: 150px">{{ trans['Montant reçu'] }} :</label>
                                        {{ numericField(['montant_recu', 'class': 'form-control', 'id' : 'montant_recu', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>
                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-info pull-left" title="{{trans['Valider']}}">
                                        {{trans['Valider']}}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <div id="changePointDeVente" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        {{ trans['Veuillez choisir le point de vente'] }}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="error_modal_container"></div>
                    <div class="form-group">
                        {{ selectStatic(['pointDeVenteList', pointDeVenteList, 'class': 'form-control', 'id': 'pointDeVenteList', 'useEmpty': true]) }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Fermer'] }}">{{ trans['Fermer'] }}</button>
                    <input type="submit" value="{{trans['Valider']}}" class="btn btn-warning changePointDeVente pull-right" title="{{trans['Valider']}}">
                </div>
            </div>
        </div>
    </div>

    <div id="openOrdonnanceList" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script>
$( document ).ready(function() {
    var width = "200px"; //Width for the select inputs
    var select2Residence = $("#residence_id").select2({
            width: width,
            placeholder: 'Selectionnez',
            allowClear: true,
            theme: "classic"
        });
    $("#_type_assurance").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_sexe").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_ogd").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_beneficiaire").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    
    var pointDeVente = {{ pointDeVente["id"] }};
    if(pointDeVente == 0){
        $("#changePointDeVente").modal("show");
    }

    $('body').on('click', '.openOrdonnanceList', function () {
        $.ajax({
            url: "{{url('caisse_pharmacie/openOrdonnanceList')}}/" + {{ pointDeVente['id'] }} + "/" + $("#id").val(),
            cache: false,
            async: true
        })
        .done(function( html ) {
            $('#openOrdonnanceList').html(html);
        });
    });

    $('body').on('click', '.changePointDeVente', function () {
        if($("#pointDeVenteList").val() == ""){
            alert("Veuillez choisir le point de vente")
            return;
        }
        $.ajax({
            url: "{{url('caisse_pharmacie/changePointDeVente/')}}" + $("#pointDeVenteList").val(),
            cache: false,
            async: true
        })
        .done(function( html ) {
            $("#changePointDeVente").modal("hide");
            window.location.reload();
        });
    });



    var select2Prestataire;
    pointDeVenteList = $("#pointDeVenteList").select2({
            width: "100%",
            placeholder: 'Sélectionnez le point de vente',
            allowClear: true,
            theme: "classic"
        });

    function addProduitToVente(s_idproduit, s_produit, s_quantite, s_prix, s_montant_total_produit, s_prix){

        if( s_idproduit == "" || s_produit == "" || s_prix == "" || s_montant_total_produit == "" || s_quantite == "" ){
            sweetAlert("Oops...", "Verifiez les données saisies", "warning");
            return;
        }

        //on test si ce produit a ete deja ajouter alors on notifie et on arrete
        var doublonTest = 0;
        $(".idproduit").each(function(){
            if( s_idproduit == $(this).val() )
            {
                sweetAlert("...", "Ce produit a été déja ajouté", "warning");
                doublonTest = 1;
                return;
            }
        });
        if( doublonTest == 1 )  {
            return;
        }

        //On test la quantité saisie
        if( parseFloat($("#s_reste").val()) < parseFloat($("#s_quantite").val()) ){
            alert("Le stock restant de ce produit ne permet pas de vendre " + $("#s_quantite").val() + " unité.");
            $("#s_quantite").focus();
            return;
        }

        var text = '<tr class="todo-list">' + 
                        '<td><input type="hidden" class="idproduit"  name="idproduit[]" value="' + s_idproduit + '" /> ' + s_produit + 
                        '</td>' +
                        '<td> <input type="hidden" class="prix" name="prix[]" value="' + s_prix + '" /> ' + s_prix + 
                        '</td>' +
                        '<td> <input type="hidden" class="quantite" name="quantite[]" value="' + s_quantite + '" /> ' + s_quantite + 
                        '</td>' +
                        '<td>' +
                            '<input type="hidden" class="montant_total_produit" name="montant_total_produit[]" value="' + s_montant_total_produit + '" /> ' + s_montant_total_produit + 
                        '</td>' +
                        '<td>' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                        '</td>'
                    '</tr>';
        $("#produitList2").append(text);

        //remise des champs a zero
        $('#s_idproduit,#s_produit,#s_montant_total_produit').val('');
        $('#s_produit').focus();
        $('#s_prix, #s_quantite, #s_reste').val('');
        $('#p_txt_div').hide();
        $('#produit_id').val('');
        $('#s_prix').val('');
    }

    $('body').on('click', '.produitAdd', function () {
        var s_idproduit, s_produit; var s_montant_total_produit; var fquantite; var s_prix; var ftotal; s_prix;
        s_idproduit                 = $('#s_idproduit').val();  
        s_produit                   = $('#s_produit').val();
        s_quantite                  = $('#s_quantite').val();
        s_prix                      = $('#s_prix').val();  
        s_montant_total_produit     = $('#s_montant_total_produit').val();
        s_prix                      = $('#s_prix').val();
        
        addProduitToVente(s_idproduit, s_produit, s_quantite, s_prix, s_montant_total_produit, s_prix);
        //Faisons maintenant le fameux calcul
        computeTheForm();
    });

    $('body').on('click', '.produitOrdonnanceAdd', function () {
        var s_idproduit, s_produit; var s_montant_total_produit; var fquantite; var s_prix; var ftotal; s_prix;
        var currentOrdPrd = $(this).closest("tr");
        s_idproduit                 = currentOrdPrd.find(".ord_idproduit").val();
        s_produit                   = currentOrdPrd.find(".ord_libelle_produit").val();
        s_quantite                  = currentOrdPrd.find(".ord_quantite").val();
        s_prix                      = currentOrdPrd.find(".ord_prix").val();
        s_montant_total_produit     = Math.ceil(parseFloat(s_prix, 10) * parseFloat(s_quantite, 10));
        
        addProduitToVente(s_idproduit, s_produit, s_quantite, s_prix, s_montant_total_produit, s_prix);
        //Faisons maintenant le fameux calcul
        computeTheForm();
        currentOrdPrd.css("background-color", "#ccf5ff");
    });

    $('body').on('click', '.produitOrdonnanceAddAll', function () {
        var s_idproduit, s_produit; var s_montant_total_produit; var fquantite; var s_prix; var ftotal; s_prix;
        var currentTable = $(this).closest("table");
        var currentTbody = currentTable.find("tbody");

        currentTbody.find("tr").each(function(){
            s_idproduit                 = $(this).find(".ord_idproduit").val();
            s_produit                   = $(this).find(".ord_libelle_produit").val();
            s_quantite                  = $(this).find(".ord_quantite").val();
            s_prix                      = $(this).find(".ord_prix").val();
            if(s_quantite > 0){
                s_montant_total_produit     = Math.ceil(parseFloat(s_prix, 10) * parseFloat(s_quantite, 10));
                addProduitToVente(s_idproduit, s_produit, s_quantite, s_prix, s_montant_total_produit, s_prix);
                //Faisons maintenant le fameux calcul
                computeTheForm();
                $(this).css("background-color", "#ccf5ff");
            }
        });
        $("#openOrdonnanceList").modal('hide');
    });

    $('body').on('change', '#_type_assurance_taux', function () {
        //Alors recalculons
        computeTheForm();
    });
    
    function computeTheForm(){
        setTimeout(function(){
            //on effectue le calcul
            var minValRecu      = 0;
            var total           = 0;
            var total_a_payer   = 0;
            var totalRest       = 0;
            var remise          = isNaN(parseFloat($("#_type_assurance_taux").val())) ? 0 : parseFloat($("#_type_assurance_taux").val());
                remise          = remise / 100;
            $(".montant_total_produit").each(function(){
                total           = Math.ceil(parseFloat(total, 10) + parseFloat($(this).val(), 10));
                totalRest       = Math.ceil(parseFloat(total, 10) * remise);
                total_a_payer   = Math.ceil(parseFloat(total, 10) - parseFloat(totalRest, 10));
            });  
            minValRecu  = Math.ceil(parseFloat(total_a_payer, 10));
            $("#total").val('').val(total);
            $("#total_a_payer").val('').val(total_a_payer);
            $("#total_reste").empty().val(totalRest);
            $("#montant_recu").attr("min", minValRecu);

        }, '300');
    }

    function displayResult(item) {
        $('#s_idproduit').val(item.value.split("|")[0]);
        $('#s_prix').val(item.value.split("|")[1]);
        $('#s_reste').val(item.value.split("|")[2]);
        $('#s_quantite').attr("max", item.value.split("|")[2]);
        var qt   = isNaN(parseFloat($("#s_quantite").val())) ? 0 : parseFloat($("#s_quantite").val());
        var montant_unitaire    = isNaN(parseFloat($("#s_prix").val())) ? 0 : parseFloat($("#s_prix").val());
        $("#s_montant_total_produit").val( qt * montant_unitaire );
        $("#s_quantite").focus();
        console.log(item.value);
    }
    function autoloader(){
        $('input.typeahead').typeahead({
            ajax: {
                url: '{{url("produit/ajaxProduitCaisse")}}/{{ pointDeVente["id"] }}',
                method: 'get',
            },
            displayField: 'libelle',
            //scrollBar:true,
            onSelect: displayResult
        });
    }
    autoloader();

    function displayResultPatient(item) {
        var current = JSON.parse(item.value);
        console.log(current);
        $("#id").val(current.id);
        $("#nom").val(current.nom);
        $("#prenom").val(current.prenom);
        $("#date_naissance").val(current.date_naissance);
        $("#_sexe").val(current.sexe).change();
        $("#adresse").val(current.adresse);
        $("#residence_id").val(current.residence_id);
        $("#telephone").val(current.telephone);
        $('#residence_id').val(current.residence_id).change();

        if(current.ass_id != "" && current.ass_id != null){
            $("#_type_assurance").val(current.ass_id).change();
        }
        else{
            $("#_type_assurance").val("").change();
        }

        $(".openOrdonnanceList").removeClass("hidden").trigger('click');
    }
    function autoloaderPatient(){
        $('input.typeahead_patient').typeahead({
            ajax: {
                url: '{{url("patients/ajaxPatient")}}',
                method: 'get',
            },
            displayField: 'libelle',
            //scrollBar:true,
            onSelect: displayResultPatient
        });
    }
    autoloaderPatient();

    $('body').on('click', '.suppElement', function () {
        var current = $(this).closest("tr");
        $(current).css('background-color', '#ff9933').fadeOut(100, function(){ $(this).remove();});
        //Alors recalculons
        computeTheForm();
    });

    $('#infos_assure').hide();
    $('body').on('change', '#_type_assurance', function () {
        var val = $(this).val();
        if(val != ""){
            var assurance_id = val.split("|")[0];
            $('#type_assurance_id').val(assurance_id);
            $('#_type_assurance_taux').val(val.split("|")[1]);
            $('#infos_assure').show();

            if($("#id").val() != ""){
                $.ajax({
                    url: "{{url('patients/getAssuranceInfos')}}/" + $("#id").val() + "/" + assurance_id,
                    cache: false,
                    async: true
                })
                .done(function( data ) {
                    var current = JSON.parse(data);
                    console.log(current);
                    $("#_numero_assurance").val(current.numero);
                    $('#_ogd').val(current.ogd).change();
                    $('#_beneficiaire').val(current.beneficiaire).change();
                    $("#_autres_infos").val(current.autres_infos);
                });
            }
        }
        else{
            $('#type_assurance_id').val("");
            $('#_type_assurance_taux').val("");
            $('#infos_assure').hide();
        }
        //Alors recalculons
        computeTheForm();
    });

    $('body').on('change', '#s_quantite', function() {     
        var qt   = isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
        var montant_unitaire    = isNaN(parseFloat($("#s_prix").val())) ? 0 : parseFloat($("#s_prix").val());
        $("#s_montant_total_produit").val( qt * montant_unitaire );
        //Refaisons une derniere fois le calcul
        computeTheForm();
    });


    $("[data-mask]").inputmask();
});
</script>