<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Dossier d'analyse médicale"] }} - {{ trans[' N°'] }} {{ dossier_id }} 
            <a class="btn btn-info" href="{{ url('labo_demandes/dossier') }}/{{ patient.id }}">
                {{trans["Retour à la liste des dossiers du patient"]}}
            </a>
            <!--  / Paillasse: {{ laboDemande.paillasse}} -->
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('patients/index') }}"><i class="fa fa-dashboard"></i> {{ trans['Dossier patient']}}</a></li>
            <li><i class="fa fa-circl-o"></i> {{ trans["Dossier d'analyse médicale"] }}</li>
            <li class="active">{{ patient.id }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content connectedSortable">
        <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="{{ url("img/user.jpg") }}" alt="User profile picture">

                  <h3 class="profile-username text-center">{{ patient.nom ~ ' ' ~  patient.prenom }}</h3>

                  <p class="text-muted text-center">{{ patient.profession }}</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>Identfiant</b> <a class="pull-right">{{ patient.id }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>ID Technique</b> <a class="pull-right">{{ patient.id_technique }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-birthday-cake margin-r-5"></i> <b>Date de naissance</b> <a class="pull-right">{{ date('d/m/Y', strtotime(patient.date_naissance)) }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Téléphone</b> <a class="pull-right">{{ patient.telephone }}</a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Autre contact</b> <a class="pull-right">{{ patient.telephone2 }}</a>
                    </li>

                    <li class="list-group-item">
                      <i class="fa fa-map-marker margin-r-5"></i> <b>Résidence</b> <a class="pull-right">{{ patient_residence }}</a>
                      <br /><a> {{ patient.adresse }}</a>
                    </li>
                  </ul>

                  <a href="{{ url("patients/dossier/")}}{{ patient.id }}" class="btn btn-primary btn-block"><b>Voir le dossier complet</b></a>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">A propos </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-book margin-r-5"></i> Dernière visite</strong>
                  <a class="pull-right">{% if sizeof(patient.getLaboDemandes()) > 0 %} {{date('d/m/Y H:i:m', strtotime( patient.getLaboDemandes(['order' : 'date Desc'])[0].date)) }} {% else %} ------- {% endif %}</a>

                  <hr>

                  <strong><i class="fa fa-warning margin-r-5"></i> Antécédents</strong>
                  <!-- <a class="btn btn-primary pull-right antecedantpop" data-antecedantid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Ajouter un antécédent" data-patientid="{{ patient.id }}">
                    <i class="glyphicon glyphicon-plus"></i>
                  </a> -->

                  <p id="antecedantlist">
                    {% for index, antecedant in patient_antecedant %}
                      <div>
                        <span class="label label-{{antecedant['niveau']}}">
                          {{ antecedant['type'] }} - {{ antecedant['libelle'] }}
                          <i class="glyphicon glyphicon-remove deleteBtnAntecedant"  data-antecedantid="{{ antecedant['id'] }}" data-antecedantname="{{ antecedant['libelle'] }}" title="Assurance" data-patientid="{{ patient.id }}"></i>
                        </span>
                      </div>
                    {% endfor %}
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Autres informations</strong>

                  <p>{{ patient.autre_infos }}</p>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
             
                      {% include "labo_demandes/editDossier.volt" %}
                 
                <!-- /.tab-content -->
            </div>
              <!-- /.nav-tabs-custom -->
          </div>
            <!-- /.col -->
        </div>
        
    </section>

</div>

<script>
  $(function () {
    $(".todo-list").sortable({
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999
    });

    $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": false, // Font styling, e.g. h1, h2, etc.
          "emphasis": false, // Italics, bold, etc.
          "lists": false, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": false, // Button to change color of font
          "blockquote": false, // Blockquote
        }
      });
  });
</script>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        } 
    }
   

    $( document ).ready(function() {

      $('body').on('submit', '#f_result', function () {
          $('body').addClass('loading');
      });

      $(".antecedantpop").popover(
      {
          placement : 'left',
          html : true, 
          template: '<div class="popover" style="max-width:500px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Fermer</a></div></div>',
          content: function() {
                    var text = "";
                    $.ajax({
                        url: "{{url('labo_demandes/antecedantPopover/')}}" + $(this).data('patientid') + "/" + $(this).data('antecedantid'),
                        cache: false,
                        async: false
                    })
                    .done(function( html ) {
                        text = html;
                    });
                    return text;
                  },
          title: "Titre",
          animation: true,
          delay: { show: 100, hide: 100 },
      });
      $(document).on("click", ".popover-footer .btn" , function(){
        $(this).parents(".popover").popover('hide');
      });
       
      $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": false, // Font styling, e.g. h1, h2, etc.
          "emphasis": false, // Italics, bold, etc.
          "lists": false, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": false, // Button to change color of font
          "blockquote": false, // Blockquote
        }
      });

      $('body').on('click', '.antibiogrammeModal', function () {
          $('body').addClass('loading');
          $('#antibiogrammeModal').html('');
          $.ajax({
              url: "{{url('labo_demandes/editAntibiogramme')}}/" + $(this).data('demandeid') + "/" + $(this).data('analyseid') + "/" + $(this).data('analysenom'),
              cache: false,
              async: true
          })
          .done(function( html ) {
              $('body').removeClass('loading');
              $('#antibiogrammeModal').html(html);
          });
      });

      $('body').on('change', '.antibiogrammeChoser', function () {
        var val = $(this).val();

        if(val != "Banque"){
            $('body').addClass('loading');
            $('#detailsDiv').html('');
            $.ajax({
                url: "{{url('labo_demandes/detailsAntibiogramme')}}/" + val,
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#detailsDiv').html(html);
            });
        }
      });

      $('body').on('click', '.validItem', function () {
            var analyse_id = $(this).data("id");
            var demande_id = $(this).data("demandeid");
            var current = $(this);

            $.ajax({
                url: "{{url('labo_demandes/validItem')}}/" + demande_id + "/" + analyse_id,
                cache: false,
                async: true
            })
            .done(function( html ) {
              current.removeClass('btn-warning');
              current.addClass('btn-success');
            });
            return false;
      });

      $('body').on('click', '.suppAtb', function () {
            var current = $(this).closest(".atb_div");
            $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
            return false;
      });

      $('body').on('submit', '.formAntibiogramme', function( event ) {
        // Stop form from submitting normally
        event.preventDefault();

        var _analyse_id = $('#_analyse_id').val();
        var rs = {};
        rs.germe  = $('#germe').val();
        rs.conclusion = $('#conclusion').val();
        rs.antibiogrammes = [];
        
        var str = '' +
            '<div class="col-xs-4 atb_div" >' +
              '<div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">' +
                '<div class="box-header with-border">' +
                  '<h3 class="box-title">'+ rs.germe +'</h3>' +
                  '<div class="box-tools pull-right">' +
                    '<button class="btn btn-box-tool suppAtb"><i class="fa fa-times"></i></button>' +
                  '</div>' +
                '</div>' +
                '<div class="box-body" >';


        var tt = $('.antibiotique_l').length;
        for (var i = 0; i < tt; i++) {
          rs.antibiogrammes.push({
                "antibiotique" : $('#antibiotique_l_' + i).val(),
                "valeur" :  $('#antibiotique_v_' + i).val()
          });
          str +=   '<div class="form-group"><b>'+$('#antibiotique_l_' + i).val()+':</b> ' + $('#antibiotique_v_' + i).val() + '</div>';
        }

        str +=     '<div class="form-group"><b>Conclusion:</b>  ' + rs.conclusion + '</div>';
        var antistr = JSON.stringify(rs);
        antistr = antistr.replace(/'/g, "&#146;");
        str +=      "<input type='hidden' name='antibiogrammes_"+_analyse_id+"[]' value='"+ antistr+"' />" +
                '</div>' +
              '</div>' +
            '</div>';
            //alert(JSON.stringify(rs));
        if (rs.antibiogrammes.length == 0) {
            sweetAlert("...", "Veuillez renseigner les antibiotiques", "warning");
            return false;
        }

        var analyse_libelle = $('#current_analyse').val();
        $("#antibigrammes_" + analyse_libelle).append(str);
        //console.log(JSON.stringify(rs));
        $('#antibiogrammeModal').modal('hide');
      });

      function ajaxLoader(){
            $('input.provenancetypeahead').typeahead({
                ajax: {
                    url: '{{url("labo_demandes/ajaxProvenance")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });

            $('input.prescripteurtypeahead').typeahead({
                ajax: {
                    url: '{{url("labo_demandes/ajaxPrescripteur")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });
      }

      function displayAntibiogramme(){

        //setTimeout(function(){
        
          $(".antibio_hidden").each(function(){

              var _name = $(this).attr('name');
              var _val = $(this).val();
              var analyse_libelle = $(this).data("analyselibelle");
              var rs = JSON.parse(_val);
              
              var str = '' +
                  '<div class="col-xs-4 atb_div" >' +
                    '<div class="box box-primary" style="width: 100%;padding: 0;margin: 0;">' +
                      '<div class="box-header with-border">' +
                        '<h3 class="box-title">'+ rs.germe +'</h3>' +
                        '<div class="box-tools pull-right">' +
                          '<button class="btn btn-box-tool suppAtb"><i class="fa fa-times"></i></button>' +
                        '</div>' +
                      '</div>' +
                      '<div class="box-body" >';

              var antibiotiques =  rs.antibiogrammes;
              for (var i = 0; i < antibiotiques.length; i++) {
                  str +=   '<div class="form-group"><b>'+antibiotiques[i].antibiotique+':</b> ' + antibiotiques[i].valeur + '</div>';
              }

              str +=     '<div class="form-group"><b>Conclusion:</b> ' + rs.conclusion + '</div>';
              var antistr = JSON.stringify(rs);
              antistr = antistr.replace(/'/g, "\\'");
              str +=      "<input type='hidden' name='"+_name+"' value='"+ antistr+"' />" +
                      '</div>' +
                    '</div>' +
                  '</div>';

              $("#antibigrammes_" + analyse_libelle).append(str);

          });

            $(".antibio_to_destroy").each(function(){
              $(this).remove();
            });
        //}, '500');

      }

      $('body').on('click', '.clotureBtn', function () {
            var demandeid = $(this).data('demandeid');
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action validera cette demande'] }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ trans["Oui, Clotûrer"] }}',
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
                            url: "{{url('labo_demandes/clotureDemande')}}/" + demandeid,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '{{ trans["Validée!"] }}',
                                    '{{ trans["La demande a été clotûrée avec succès."] }}',
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

      ajaxLoader();
      displayAntibiogramme();

      submitAjaxForm();


    });
</script>