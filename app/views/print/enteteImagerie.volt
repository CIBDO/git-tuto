
<div class="row no-print">
    <div class="col-sm-3">
      <a class="btn btn-default pull-left" href="{{ referer }}"><i class="fa fa-arrow-left"></i>{{ trans['Retour'] }}</a>
    </div>
    <div class="col-sm-3">
      <a onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Imprimer</a>
    </div>
     <div class="col-sm-3">
      <a href="{{ url('print/imgDemandeEtiquette') }}/{{ imgDemande.id }}" target="_blank" class="btn btn-default"><i class="fa fa-envelope"></i> Imprimer l'etiquette</a>
    </div>
</div>

<div class="row">
  <div class="col-sm-12">

  {% if entete.type_entete == "l" %}
    
    {% if entete.template_logo == "left" %}

      {% if entete.logo != "" %}
        <img class="img-responsive pad" style="max-height:135px;float:left" src="{{ static_url("img/structure/") }}{{ entete.logo }}" alt="Logo">
      {% endif %}

      <table class="table table-borderless" style="display:inline !important">
        <tr><td>{{ entete.ligne1 }}</td></tr>
        <tr><td>{{ entete.ligne2 }}</td></tr>
        <tr><td>{{ entete.ligne3 }}</td></tr>
        <tr><td>{{ entete.ligne4 }}</td></tr>
      </table>

    {% endif %}

    {% if entete.template_logo == "right" %}

      {% if entete.logo != "" %}
        <img class="img-responsive pad" style="max-height:135px;float:right" src="{{ static_url("img/structure/") }}{{ entete.logo }}" alt="Logo">
      {% endif %}

      <table class="table table-borderless" style="display:inline !important">
        <tr><td>{{ entete.ligne1 }}</td></tr>
        <tr><td>{{ entete.ligne2 }}</td></tr>
        <tr><td>{{ entete.ligne3 }}</td></tr>
        <tr><td>{{ entete.ligne4 }}</td></tr>
      </table>

    {% endif %}

    {% if entete.template_logo == "top" %}
    <center>
      {% if entete.logo != "" %}
        <img class="img-responsive pad" style="max-height:135px" src="{{ static_url("img/structure/") }}{{ entete.logo }}" alt="Logo">
      {% endif %}

      <table class="table table-borderless table-nomargin">
        <tr align="center"><td style="padding: 0px; margin:0px">{{ entete.ligne1 }}</td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px">{{ entete.ligne2 }}</td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px">{{ entete.ligne3 }}</td></tr>
        <tr align="center"><td style="padding: 0px; margin:0px">{{ entete.ligne4 }}</td></tr>
      </table>
    </center>
    {% endif %}

  {% elseif entete.type_entete == "e" %}

    {% if entete.logo != "" %}
      <img class="img-responsive pad" style="max-height:150px;float:left" src="{{ static_url("img/structure/") }}{{ entete.logo }}" alt="Logo">
    {% endif %}

  {% endif %}

  </div>
</div>