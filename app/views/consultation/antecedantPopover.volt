

<div>
    <form action="{{ url('consultation/addAntecedant/' ~ id) }}" class="form formAntecedant" method="post">
        <div class="form-group">
            <label for="type" class="control-label">{{ trans['Type'] }}</label><br />
            {{ select(['type', ['Médicaux' : 'Médicaux', 'Chirurgicaux' : 'Chirurgicaux', 'Gynéco-obstétrique' : 'Gynéco-obstétrique', 'Familiaux' : 'Familiaux'], 'id' : '_type', 'emptyText' : 'Choisir', 'useEmpty' : true]) }}
        </div>
        <div class="form-group">
            <label for="libelle" class="control-label">{{ trans['Libellé'] }}</label><br />
            {{ textField(['libelle', 'id' : '_libelle', 'placeholder' : 'libelle']) }}
        </div>
        <div class="form-group">
            <label for="niveau" class="control-label">{{ trans['Niveau'] }}</label><br />
            {{ select(['niveau', ['normal' : 'normal', 'moyen' : 'moyen', 'important' : 'important'], 'id' : '_niveau', 'emptyText' : 'Choisir', 'useEmpty' : true]) }}
            {{ hiddenField(['patients_id', 'class': 'form-control', 'id' : '_patients_id', 'value': patients_id]) }}

        </div>
        <button type="button" data-id="{{ id }}" class="btn btn-warning pull-right poOverOk">Ok</button>
    </form>
</div>
            
<script>
var width = "100%"; //Width for the select inputs
/*$("#_type").select2({width: width, placeholder: "choisir", theme: "classic"});
$("#_niveau").select2({width: width, placeholder: "choisir", theme: "classic"});*/

$(".poOverOk").each(function(){
    $(this).click(function(){
       
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
                alert("Verifier les champs renseignés");
            }
            else{
                var libelle = $("#_libelle").val();
                var niveau = $("#_niveau option:selected").val();
                var type = $("#_type option:selected").val();
                if(niveau == "normal"){
                    niveau = "primary";
                }
                if(niveau == "moyen"){
                    niveau = "warning";
                }
                if(niveau == "important"){
                    niveau = "danger";
                }

                var text = '<div>' +
                                '<span class="label label-' + niveau + '">' + type + " - " + libelle + 
                                '<i class="glyphicon glyphicon-remove deleteBtnAntecedant"  data-antecedantid="'+data+'" data-antecedantname="'+libelle+'" title="Assurance"></i>' +
                                '</span>' +
                            '</div>';

                $("#antecedantlist").append(text);
            }
        });
    });
});
</script>