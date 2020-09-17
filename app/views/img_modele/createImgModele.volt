<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
            {% if form_action == 'edit' %}
                {{ trans["Modifier un modele"] }}
            {% else %}
                {{ trans["Créer un modele"] }}
            {% endif %}
            </h4>
        </div>
        {% if form_action == 'edit' %}
        <form action="{{ url('img_modele/' ~ form_action ~ 'ImgModele/' ~ imgModele_id) }}" class="form ajaxForm" method="post">
        {% else %}
        <form action="{{ url('img_modele/' ~ form_action ~ 'ImgModele/') }}" class="form ajaxForm" method="post">
        {% endif %}
            <div class="modal-body">
                <div class="error_modal_container"></div>

                <div class="form-group">
                    <label for="keyword" class="control-label">{{ trans['Mots clés'] }} </label><i> (Separés par des virgules)</i>
                    {{ form.render('keyword') }}
                </div>

                <div class="form-group">
                    <label for="interpretation" class="control-label">{{ trans['Interpretation'] }}</label>
                    {{ form.render('interpretation') }}
                </div>

                <div class="form-group">
                    <label for="conclusion" class="control-label">{{ trans['Conclusion'] }}</label>
                    {{ form.render('conclusion') }}
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="{{ trans['Close'] }}">{{ trans['Close'] }}</button>
                <input type="submit" value="{{trans['Save']}}" class="btn btn-success pull-right" title="{{trans['Save']}}">
            </div>
        </form>
    </div>
</div>

<script>
    var width = "100%"; //Width for the select inputs
    $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": true, // Font styling, e.g. h1, h2, etc.
          "emphasis": true, // Italics, bold, etc.
          "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": true, // Button to change color of font
          "blockquote": false, // Blockquote
        }
    });
</script>
