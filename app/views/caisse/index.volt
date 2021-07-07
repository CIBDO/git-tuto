<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Caisse de vente de ticket'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Caisse']}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Vente de ticket'] }}</li>
        </ol>
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
                                        <input type="text" style="width: 100%" name="s_patient" id="s_patient" class="form-control typeahead_patient" autocomplete="off" value="" placeholder="Recherche mutilicritères" />
                                    </div>
                                        
                                </div>
                            </div> 

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="Services" style="width: 100px">{{ trans['Identifiant']}} :</label>
                                        {{ textField(['id', 'id' : 'id', 'class': 'form-control']) }}
                                    </div>
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="prenom" style="width: 100px">{{ trans['Prénom']}} :</label>
                                        {{ textField(['prenom', 'id' : 'prenom', 'class': 'form-control', 'required' : 'required']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="nom" style="width: 100px">{{ trans['Nom']}} :</label>
                                        {{ textField(['nom', 'id' : 'nom', 'class': 'form-control', 'required' : 'required']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="date_naissance" style="width: 100px">{{ trans['Naissance']}} :</label>
                                        {{ dateField(['date_naissance', 'id' : 'date_naissance', 'class': 'form-control', 'id': 'date_naissance', 'required' : 'required']) }}
                                    </div>
                                        
                                </div>
                            </div>  

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="telephone" style="width: 100px">{{ trans['Téléphone']}} :</label>
                                        {{ textField(['telephone', 'id' : 'telephone', 'class': 'form-control', 'data-inputmask' : '"mask": "99-99-99-99"', 'data-mask' : '']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="residence_id" style="width: 100px">{{ trans['Résidence']}} :</label>
                                        {{ selectStatic(['residence_id', residences, 'using' : ['id', 'libelle'], 'class': 'form-control', 'id': 'residence_id', 'useEmpty': true]) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="adresse" style="width: 100px">{{ trans['Adresse']}} :</label>
                                        {{ textField(['adresse', 'id' : 'adresse', 'class': 'form-control']) }}
                                    </div>
                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4">
                                        <label for="sexe" style="width: 100px">{{ trans['Sexe']}} :</label>
                                        {{ select(['sexe',  ['m' : 'Masculin', 'f' : 'Feminin'], 'useEmpty' : true, 'class': 'form-control', 'id' : '_sexe', 'required' : 'required']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="num_quittance" style="width: 100px">{{ trans['Quittance']}} :</label>
                                        {{ textField(['num_quittance', 'class': 'form-control']) }}
                                    </div>

                                    <div class="form-group  col-md-4">
                                        <label for="typeAssurance" style="width: 100px">{{ trans["Prise en charge"]}} :</label>
                                        <input type="hidden"  name="type_assurance_id" id="type_assurance_id" value="" /> 
                                        {{ select(['type_assurance', typeAssurancelist, 'class': 'form-control', 'using' : ['id', 'libelle'], 'useEmpty' : true, 'id' : '_type_assurance']) }}
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
                                        {{ select(['ogd', ['inps' : 'inps', 'cmss' : 'cmss'], 'class': 'form-control', 'id' : '_ogd', 'useEmpty' : true]) }}
                                    </div>
                                    <div class="form-group  col-md-2">
                                        <label for="beneficiaire" style="width: 100px">{{ trans['Bénéficiaire']}} :</label><br>
                                        {{ select(['beneficiaire', ['titulaire' : 'titulaire', 'enfant' : 'enfant', 'parent' : 'parent'], 'class': 'form-control', 'id' : '_beneficiaire', 'useEmpty' : true]) }}
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
                                                <th> Prestation </th>
                                                <th> Prestataire </th>
                                                <th> Quantité </th>
                                                <th> Montant </th>
                                            </thead>
                                            <tbody id="prestationList2">
                                                <tr class="todo-list"> 
                                                    <td>
                                                        <input type="hidden"  name="s_idacte" id="s_idacte" value="" /> 
                                                        <input type="text"  name="s_acte" id="s_acte" class="form-control typeahead" size="35" autocomplete="off" value="" placeholder="Prestation" />
                                                    </td>
                                                    <td> 
                                                        {{ selectStatic(['s_prestataire', prestataires, 'using' : ['id', 'nom'], 'class': 'form-control', 'id': 's_prestataire', 'useEmpty': true]) }}
                                                    </td>
                                                    <td> 
                                                        <input type="number" style="width:70px"  name="s_quantite" id="s_quantite" min="1" class="form-control" autocomplete="off" value="1" placeholder="Quantité" />
                                                    </td>
                                                    <td>
                                                        <input type="hidden"  name="h_montantacte" id="h_montantacte" value="" /> 
                                                        <input type="hidden"  name="h_difference" id="h_difference" value="" /> 
                                                        <input type="hidden"  name="s_montant_total_acte_diff" id="s_montant_total_acte_diff" value="" /> 
                                                        <input type="number" style="width:100px"  name="s_montant_total_acte" id="s_montant_total_acte" min="0" class="form-control" autocomplete="off" value="" readonly="readonly" placeholder="Total" />
                                                    </td>
                                                    <td>
                                                        <a style="cursor: pointer;" class="btn btn-default prestationAdd" title="Ajouter une prestation">
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
                                        <label for="total_reste" style="width: 150px">{{ trans['Reste à payer'] }} :</label>
                                        {{ numericField(['total_reste_a_payer', 'class': 'form-control', 'id' : 'total_reste_a_payer', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px" id="div_total_difference">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="total_a_payer" style="width: 150px">{{ trans['Difference'] }} :</label>
                                        {{ numericField(['total_difference', 'class': 'form-control', 'id' : 'total_difference', 'required' : 'required']) }}
                                    </div>                                        
                                </div>
                            </div>

                            <div class="row" style="margin-top : 10px">
                                <div class="col-md-12" >
                                    <div class="form-group  col-md-4 pull-right">
                                        <label for="total_a_payer" style="width: 150px">{{ trans['Total à percevoir'] }} :</label>
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

</div>

<script>
$( document ).ready(function() {
    var select2Prestataire;
    var width = "200px"; //Width for the select inputs
    var width100 = "100px"; //Width for the select inputs
    var select2Residence = $("#residence_id").select2({
            width: width,
            placeholder: 'Selectionnez',
            allowClear: true,
            theme: "classic"
        });
    $("#_sexe").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_type_assurance").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_ogd").select2({width: width100, allowClear: true, placeholder: "choisir", theme: "classic"});
    $("#_beneficiaire").select2({width: width100, allowClear: true, placeholder: "choisir", theme: "classic"});
    
    select2Prestataire = $("#s_prestataire").select2({
            width: width,
            placeholder: 'Sélectionnez le prestataire',
            allowClear: true,
            theme: "classic"
        });
    $('body').on('click', '.prestationAdd', function () {
        var s_idacte, s_acte; var s_montant_total_acte; var s_montant_total_acte_diff; var fquantite; var h_montantacte; var h_difference; var ftotal; var s_idprestataire, s_prestataire;

        s_idacte        = $('#s_idacte').val();  
        s_acte          = $('#s_acte').val();
        s_idprestataire = $('#s_prestataire').val();  
        s_prestataire   = $('#s_prestataire option:selected').html();
        s_montant_total_acte   = $('#s_montant_total_acte').val();
        s_montant_total_acte_diff   = $('#s_montant_total_acte_diff').val();
        h_montantacte   = $('#h_montantacte').val();
        h_difference    = $('#h_difference').val();
        s_quantite      = $('#s_quantite').val();
        
        if( s_idacte == "" || s_acte == "" || s_prestataire == "" || s_montant_total_acte == "" || s_quantite == "" ){
            sweetAlert("Oops...", "Verifiez que toutes les informations sur la prestation ont été saisies", "warning");
            return;
        }

        //on test si ce produit a été déja ajouté alors on notifie et on arrête
        var doublonTest = 0;
        $(".idacte").each(function(){
            if( s_idacte == $(this).val() )
            {
                sweetAlert("...", "Cette prestation a été déja ajoutée au ticket", "warning");
                doublonTest = 1;
                return;
            }
        });
        if( doublonTest == 1 )  {
            return;
        }

        var relicat = ( s_montant_total_acte_diff > 0 ) ? "(+ "+s_montant_total_acte_diff+")" : "";

        var text = '<tr class="todo-list">' + 
                        '<td><input type="hidden" class="idacte"  name="idacte[]" value="' + s_idacte + '" /> ' + s_acte + '</td>' +
                        '<td> <input type="hidden" class="prestataire" name="prestataire[]" value="' + s_idprestataire + '" /> ' + s_prestataire + '</td>' +
                        '<td> <input type="hidden" class="quantite" name="quantite[]" value="' + s_quantite + '" /> ' + s_quantite + ' </td>' +
                        '<td>' +
                            '<input type="hidden" class="montant_total_acte_diff" name="montant_total_acte_diff[]" value="' + s_montant_total_acte_diff + '" /> ' + 
                            '<input type="hidden" class="montant_total_acte" name="montant_total_acte[]" value="' + s_montant_total_acte + '" /> ' + s_montant_total_acte + relicat +
                            '<input type="hidden" class="montant_unitaire_acte" name="montant_unitaire_acte[]" value="' + h_montantacte + '" /> ' + 
                            '<input type="hidden" class="montant_unitaire_difference" name="montant_unitaire_difference[]" value="' + h_difference + '" /> ' + 
                        '</td>' +
                        '<td>' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                        '</td>'
                    '</tr>';
        $("#prestationList2").append(text);

        //remise des champs a zero
        $('#s_idacte,#s_acte,#s_montant_total_acte,#s_montant_total_acte_diff').val('');
        $('#s_acte').focus();
        $('#s_quantite').val('1');
        $('#p_txt_div').hide();
        $('#acte_id').val('');
        $('#h_montantacte').val('');
        $('#h_difference').val('');
        $('#s_prestataire option[value=""]').attr('selected','selected');
        select2Prestataire.val("").trigger("change");

        //Faisons maintenant le fameux calcul
        computeTheForm();
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
            var totalRelicat    = 0;
            var total_a_payer   = 0;
            var totalRest       = 0;
            var total_reste_a_payer       = 0;
            var remise          = isNaN(parseFloat($("#_type_assurance_taux").val())) ? 0 : parseFloat($("#_type_assurance_taux").val());
                remise          = remise / 100;
            $(".montant_total_acte").each(function(){
                total           = Math.ceil(parseFloat(total, 10) + parseFloat($(this).val(), 10));
                totalRest       = Math.ceil(parseFloat(total, 10) * remise);
                total_a_payer   = Math.ceil(parseFloat(total, 10) - parseFloat(totalRest, 10));
            });
            $(".montant_total_acte_diff").each(function(){
                totalRelicat    = Math.ceil(parseFloat(totalRelicat, 10) + parseFloat($(this).val(), 10));
            });
            total_a_payer = total_a_payer + totalRelicat;
            total_reste_a_payer = total - totalRest;
            minValRecu  = Math.ceil(parseFloat(total_a_payer, 10));
            $("#total").val('').val(total);
            $("#total_a_payer").val('').val(total_a_payer);
            $("#total_difference").val('').val(totalRelicat);
            $("#total_reste").empty().val(totalRest);
            $("#total_reste_a_payer").empty().val(total_reste_a_payer);
            $("#montant_recu").attr("min", minValRecu);

            if(totalRelicat == 0){
                $("#div_total_difference").hide();
            }
            else{
                $("#div_total_difference").show();
            }

        }, '300');
    }

    function displayResult(item) {
        $('#s_idacte').val(item.value.split("|")[0]);
        var priceArray = JSON.parse(item.value.split("|")[2]);
        console.log(priceArray);
        var prix_normale            = isNaN(parseFloat( item.value.split("|")[1] )) ? 0 : parseFloat( item.value.split("|")[1] );
        var prix_prise_en_charge    = prix_normale;
        var prix_difference         = 0;
        jQuery.each(priceArray, function(index, price) {
            console.log(price);
            if( price.type_assurance_id == $("#type_assurance_id").val() ){
                prix_prise_en_charge = price.prix;
                if(price.relicat == "1"){
                    prix_difference = ( prix_normale > prix_prise_en_charge ) ? prix_normale - prix_prise_en_charge : 0;
                }
            }
        });

        $('#h_montantacte').val(prix_prise_en_charge);
        var montant_unitaire    = prix_prise_en_charge;
        $('#h_difference').val(prix_difference);
        var qt   = isNaN(parseFloat($("#s_quantite").val())) ? 0 : parseFloat($("#s_quantite").val());
        $("#s_montant_total_acte").val( qt * montant_unitaire );
        $("#s_montant_total_acte_diff").val( qt * prix_difference );
        //$(".select2").trigger("click");
        select2Prestataire.select2("open");
    }
    function autoloader(type_id){
        var type = (typeof type_id !="undefined") ? type_id : "";
        $('input.typeahead').typeahead({
            ajax: {
                url: '{{url("actes/ajaxCaisse")}}/' + type,
                method: 'get',
                param: {test: type}
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
        $("#telephone").val(current.telephone);
        $('#residence_id').val(current.residence_id).change();

        if(current.ass_id != "" && current.ass_id != null){
            $("#_type_assurance").val(current.ass_id).change();
        }
        else{
            $("#_type_assurance").val("").change();
        }
            
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
            autoloader(assurance_id);
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
            $('#type_assurance_id, #_type_assurance_taux').val("");
            $('#_numero_assurance, #_ogd, #_beneficiaire, #_autres_infos').val("");
            $('#infos_assure').hide();
        }
        //Alors recalculons
        computeTheForm();
    });

    $('body').on('change', '#s_quantite', function() {     
        var qt   = isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
        var montant_unitaire    = isNaN(parseFloat($("#h_montantacte").val())) ? 0 : parseFloat($("#h_montantacte").val());
        var montant_unitaire_difference    = isNaN(parseFloat($("#h_difference").val())) ? 0 : parseFloat($("#h_difference").val());
        $("#s_montant_total_acte").val( qt * montant_unitaire );
        $("#s_montant_total_acte_diff").val( qt * montant_unitaire_difference );
        //Refaisons une derniere fois le calcul
        computeTheForm();
    });


    $("[data-mask]").inputmask();
});
</script>