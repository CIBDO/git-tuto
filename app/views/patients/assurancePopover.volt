

<div>
<form action="{{ url('patients/addAssurance/' ~ id) }}" class="form formAssurance" method="post">
    <div class="form-group">
        <label for="type_assurance_id" class="control-label">{{ trans['Organisme'] }}</label><br />
        {{ select(['type_assurance_id', typeAssurance , 'using' : ['id', 'libelle'], 'useEmpty' : true, 'emptyText' : 'Choisir', 'id' : '_type_assurance_id']) }}
    </div>
    <div class="form-group">
        <label for="numero" class="control-label">{{ trans['Numéro'] }}</label><br />
        {{ textField(['numero', 'id' : '_numero', 'placeholder' : 'numero']) }}
    </div>
    <div class="form-group">
        <label for="ogd" class="control-label">{{ trans['OGD'] }}</label><br />
        {{ select(['ogd', ['inps' : 'inps', 'cmss' : 'cmss'], 'id' : '_ogd', 'useEmpty' : 'true', 'emptyText' : 'Choisir']) }}
    </div>
    <div class="form-group">
        <label for="beneficiaire" class="control-label">{{ trans['Bénéficiaire'] }}</label><br />
        {{ select(['beneficiaire', ['titulaire' : 'titulaire', 'enfant' : 'enfant', 'parent' : 'parent'], 'id' : '_beneficiaire', 'useEmpty' : 'true', 'emptyText' : 'Choisir']) }}
    </div>
    <div class="form-group">
        <label for="autres_infos" class="control-label">{{ trans['Autres infos'] }}</label><br />
        {{ textField(['autres_infos', 'id' : '_autres_infos', 'placeholder' : 'autres_infos']) }}
        {{ hiddenField(['patients_id', 'class': 'form-control', 'id' : '_patients_id', 'value': patients_id]) }}
    </div>
    <div class="form-group">
        <label for="default" class="control-label">{{ trans['Prémier choix'] }}</label>
        {{ checkField(["default", "value" : "1"]) }}
    </div>
    <button type="button" data-id="{{ id }}" class="btn btn-warning pull-right poOverOk">Ok</button>
</form>
</div>
            
<script>
var width = "100%"; //Width for the select inputs
/*$("#_type_assurance_id").select2({width: width, placeholder: "choisir", theme: "classic"});
$("#_ogd").select2({width: width, placeholder: "choisir", theme: "classic"});
$("#_beneficiaire").select2({width: width, placeholder: "choisir", theme: "classic"});*/

$(".poOverOk").each(function(){
    $(this).click(function(){
       
        var modif = '{{ id }}';
        if(modif == '0'){
            if($('#ass_'+$("#_type_assurance_id option:selected").val()).length > 0) {
                swal('{{ trans["Cet organisme est déja dans la liste!"] }}', '', 'warning');
                return;
            }
        }
        $('body').addClass('loading');
        // Get some values from elements on the page:
        var $form   = $(this).closest("form"),
            url     = $form.attr('action'),
            data    = $form.serialize();

        // Send the data using post
        var posting = $.post(url, data);

        // Put the results in a div
        posting.done(function( data ) {
            $('body').removeClass('loading');
            if(data == 0) {
                swal('{{ trans["Verifier les champs renseignés"] }}', '', 'warning');
            }
            else{
                
                var text = '<tr id="tr_'+ data +'"> ' +
                                '<td id="ass_'+ $("#_type_assurance_id option:selected").val() +'"> ' + $("#_type_assurance_id option:selected").html() + ' </td>' +
                                '<td> ' + $("#_numero").val() + ' </td>' +
                                '<td> ' + $("#_ogd").val() + ' </td>' +
                                '<td> ' + $("#_beneficiaire").val() + ' </td>' +
                                '<td> ' + $("#_autres_infos").val() + ' </td>' +
                                '<td> ' +
                                    '<a  class="label label-warning assurancepop" tabindex="1" data-assuranceid="' + data + '" data-toggle="popover" data-trigger="click" title="Assurance" data-patientid="' + $("#_patients_id").val() + '">' +
                                        '<i class="glyphicon glyphicon-edit"></i>' +
                                    '</a>' +
                                      '&nbsp;&nbsp;&nbsp;' +
                                    '<span class="label label-danger deleteBtnAssurance" data-assuranceid="' + data + '" data-assurancename="' + $("#_type_assurance_id option:selected").html() + '" title="Assurance" data-patientid="' + $("#_patients_id").val() + '">' +
                                        '<i class="glyphicon glyphicon-remove"></i>' +
                                    '</span>' +
                                '</td>' +
                            '</tr>';

                if(modif != '0'){
                    $("#tr_" + data).css('background-color', '#ff9933').fadeOut(500, function(){ $(this).remove();});
                }

                $("#assurancelist tbody").append(text);
                
            }
        });
    });
});
</script>