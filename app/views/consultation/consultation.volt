<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Dossier de consultation'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('patients/index') }}"><i class="fa fa-dashboard"></i> {{ trans['Dossier patient']}}</a></li>
            <li><i class="fa fa-circl-o"></i> {{ trans['Dossier de consultation'] }}</li>
            <li class="active">{{ patient.id }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content connectedSortable">
        <div class="row">
            <div class="col-md-4">

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
                  <a class="pull-right">{% if sizeof(patient.getDossiersConsultations()) > 0 %} {{date('d/m/Y H:i:m', strtotime( patient.getDossiersConsultations(['order' : 'date_creation Desc'])[0].date_creation)) }} {% else %} ------- {% endif %}</a>

                  <hr>

                  <strong><i class="fa fa-warning margin-r-5"></i> Antécédents</strong>
                  <a class="btn btn-primary pull-right antecedantpop" data-antecedantid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Ajouter un antécédent" data-patientid="{{ patient.id }}">
                    <i class="glyphicon glyphicon-plus"></i>
                  </a>

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
            <div class="col-md-8">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#dossier_cons" data-toggle="tab" aria-expanded="false">Dossiers</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="dossier_cons">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-12">

                            <div class="btn-group pull-left">
                              <button type="button" class="btn btn-info">{{trans['Créer un dossier de consultation']}}</button>
                              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                              {% if count(formList)>0 %}
                                {% for index, form in formList %}
                                  <li><a href="#" class="createCons" data-patientid="{{ patient.id }}" data-formid="{{ form['id'] }}" data-toggle="modal" data-target="#createConsultation">{{ form['libelle'] }}</a></li>
                                {% endfor %}
                              {% else %}
                                  <li>Aucun formulaire n'est associé a votre compte</li>
                              {% endif %}
                              </ul>
                            </div>

                            <!-- <button type="button" class="btn btn-primary pull-right createCons" title="{{trans['Créer un dossier de consultation']}}" data-patientid="{{ patient.id }}" data-toggle="modal" data-target="#createConsultation">
                                {{trans['Créer un dossier de consultation']}}
                            </button> -->
                          </div>
                        </div>
                        <div class="content">
                            <div class="table-responsive">
                                <table  id="table-javascript">
                                    <thead class="bg-aqua-gradient">
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>

              </div>
                <!-- /.tab-content -->
            </div>
              <!-- /.nav-tabs-custom -->
          </div>
            <!-- /.col -->
        </div>
        
    </section>

    <div id="createConsultation" class="modal largemodal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    <div id="createSuivi" class="modal largemodal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
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

    function actionsFormatter(value, row, index){

        var txt = '<button type="button" class="btn btn-warning editSuivi" title="{{trans["Edit"]}}" data-patientid="'+ row.patients_id +'" data-dossierid="'+ row.id +'" data-suiviid="0" data-toggle="modal" data-target="#createSuivi"><i class="fa fa-sticky-note-o"></i></button>' +
                    '<button type="button" class="btn btn-info editCons" title="{{trans["Edit"]}}" data-patientid="'+ row.patients_id +'" data-dossierid="'+ row.id +'" data-formid="'+ row.form_type_id +'" data-toggle="modal" data-target="#createConsultation"><i class="fa fa-edit"></i></button>' +
                  '</div>';

                  return txt;

          //'<button type="button" class="btn btn-warning pull-right editSuivi" data-formid="4" title="suivi" data-patientid="'+ row.patients_id +'" data-dossierid="'+ row.id +'" data-suiviid="0" data-toggle="modal" data-target="#createSuivi"><i class="fa fa-sticky-note-o"></i></button>';
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

      $(".antecedantpop").popover(
      {
          placement : 'left',
          html : true, 
          template: '<div class="popover" style="max-width:500px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Fermer</a></div></div>',
          content: function() {
                    var text = "";
                    $.ajax({
                        url: "{{url('consultation/antecedantPopover/')}}" + $(this).data('patientid') + "/" + $(this).data('antecedantid'),
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

        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        $('body').on('click', '.createCons', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('consultation/createConsultation')}}/" + $(this).data('patientid') + "/0/" + $(this).data('formid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createConsultation').html(html);
            });
        });

        $('body').on('click', '.editCons', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('consultation/createConsultation')}}/" + $(this).data('patientid') + "/" + $(this).data('dossierid') + "/" + $(this).data('formid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createConsultation').html(html);
            });
        });

        $('body').on('click', '.editSuivi', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('consultation/createSuivi')}}/" + $(this).data('patientid') + "/" + $(this).data('dossierid') + "/" + $(this).data('suiviid') + "/" + $(this).data('formid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createSuivi').html(html);
            });
        });

        //consultation
        $('body').on('click', '.constanteAdd', function () {
            var text = '<li>'+
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="text"  name="cons_cle[]" autocomplete="off" value="" placeholder="Constante" /> - ' +
                            '<input type="text"  name="cons_valeur[]" autocomplete="off" value="" placeholder="Valeur" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#constList").append(text);
                ajaxLoader();
        });

        $('body').on('click', '.hypotheseAdd', function () {
            var text = '<li>'+
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="text"  name="hypothese[]" class="diagnostiquetypeahead" size="50" autocomplete="off" value="" placeholder="Hypothèse" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#hypotheseList").append(text);
                ajaxLoader();
        });

        $('body').on('click', '.diagnostiqueAdd', function () {
            var text = '<li>'+
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="text"  name="diagnostique[]" class="diagnostiquetypeahead" size="50" autocomplete="off" value="" placeholder="Diagnostique" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#diagnostiqueList").append(text);
                ajaxLoader();
        });

        $('body').on('click', '.prescriptionAdd', function () {
            var text = '<li>'+
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="hidden" name="medicament_id[]" class="medicament_id" value="" />' +
                            '<input type="text" required="required"  name="medicament[]" style="width: 260px" class="pharmacietypeahead" autocomplete="off" value="" placeholder="Médicament" /> - ' +
                            '<input type="number" required="required"  name="quantite[]" style="width: 70px" autocomplete="off" value="" placeholder="Quantité" /> - ' +
                            '<input type="text" required="required" name="mode[]" style="width: 100px" autocomplete="off" value="" class="pharmaciemodetypeahead" placeholder="Mode" /> -' +
                            '<input type="text" required="required"  name="posologie[]" style="width: 70px" autocomplete="off" value="" class="pharmacieposologietypeahead" placeholder="Posolologie" /> - ' +
                            '<input type="text"  name="duree[]" style="width: 70px" autocomplete="off" value="" class="pharmaciedureetypeahead" placeholder="Durée" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#prescList").append(text);
                ajaxLoader();
        });

        //suivi
        $('body').on('click', '.s_constanteAdd', function () {
            var text = '<li>' +
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="text"  name="cons_cle[]" autocomplete="off" value="" placeholder="Constante" /> - ' +
                            '<input type="text"  name="cons_valeur[]" autocomplete="off" value="" placeholder="Valeur" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#s_constList").append(text);
                ajaxLoader();
        });

        $('body').on('click', '.s_prescriptionAdd', function () {
            var text = '<li>'+
                          '<span class="handle">' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                            '<i class="fa fa-ellipsis-v"></i>' +
                          '</span>' +
                          '<span class="text">' +
                            '<input type="hidden" name="medicament_id[]" class="medicament_id" value="" />' +
                            '<input type="text"  required="required" name="medicament[]" style="width: 260px" class="pharmacietypeahead" autocomplete="off" value="" placeholder="Médicament" /> - ' +
                            '<input type="number"  required="required" name="quantite[]" style="width: 60px" autocomplete="off" value="" placeholder="Quantité" /> - ' +
                            '<input type="text" required="required" name="mode[]" style="width: 100px" autocomplete="off" value="" class="pharmaciemodetypeahead" placeholder="Mode" /> - ' +
                            '<input type="text"  required="required" name="posologie[]" style="width: 70px" autocomplete="off" value="" class="pharmacieposologietypeahead" placeholder="Posolologie" /> - ' +
                            '<input type="text" required="required"  name="duree[]" style="width: 70px" autocomplete="off" value="" class="pharmaciedureetypeahead" placeholder="Durée" />' +
                          '</span>' +
                          '<div class="tools">' +
                            '<i class="fa fa-trash-o suppElement"></i>' +
                          '</div>' +
                        '</li>';

                $("#s_prescList").append(text);
                ajaxLoader();
        });

        $('body').on('click', '.suppElement', function () {
            var current = $(this).closest("li");
            $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
        });

        function ajaxLoader(){
            $('input.diagnostiquetypeahead').typeahead({
                ajax: {
                    url: '{{url("consultation/" ~ structureConfig.diagnostic_source)}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });

            function displayResult(item) {
                console.log(item.value);
                var current = JSON.parse(item.value);
                setTimeout(function(){
                  var currentMedId = $(":focus").closest("li").find(".medicament_id").val(JSON.parse(item.value).id);
                }, 900);
            }
            $('input.pharmacietypeahead').typeahead({
                ajax: {
                    url: '{{url("produit/ajaxProduit")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true,
                onSelect: displayResult
            });

            $('input.pharmaciemodetypeahead').typeahead({
                ajax: {
                    url: '{{url("consultation/ajaxPrescriptionMode")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
            });

            $('input.pharmacieposologietypeahead').typeahead({
                ajax: {
                    url: '{{url("consultation/ajaxPrescriptionPosologie")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });

            $('input.pharmaciedureetypeahead').typeahead({
                ajax: {
                    url: '{{url("consultation/ajaxPrescriptionDuree")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });

            $('input.examcomplementaire').typeahead({
                ajax: {
                    url: '{{url("actes/ajaxActeLabo")}}',
                    method: 'get',
                },
                displayField: 'libelle',
                scrollBar:true
                //onSelect: displayResult
            });
        }

        $('body').on('click', '.deleteBtnAntecedant', function () {
            var id = $(this).data('antecedantid');
            var currentDiv = $(this).closest("div");
            swal(
                {
                    title: '{{ trans["Etes vous sûre?"] }}',
                    text: "{{ trans["Cette action va supprimer l'antécedent nommé:"] }} '" + $(this).data('antecedantname') + "' des informations de ce patient",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ trans["Oui, supprimer!"] }}',
                    cancelButtonText: '{{ trans["Non, annuler!"] }}',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: true,
                    closeOnCancel: true
                })
                .then(function() {
                    $('body').addClass('loading');
                    $.ajax({
                        url: "{{url('consultation/deleteAntecedant')}}/" + id,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                           /* swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["L'organisme a été supprimé des informations de ce patient avec succès."] }}',
                                'success'
                            );*/
                            $(currentDiv).css('background-color', 'red').fadeOut(300, function(){ $(this).remove();});
                            //$('body').addClass('loading');
                            //window.location.reload();
                        }
                        else{
                            swal(
                                '{{ trans["Annulé!"] }}',
                                "{{ trans['Annulé'] }}",
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

        function detailFormatter(index, row, element){ 
           $.ajax({
                url: "{{url('consultation/dossierSuivis')}}/" + row.id,
                cache: false,
                async: true
            })
            .done(function( html ) {
              return element.html(html);
            });
        }

        $('#table-javascript').bootstrapTable({
        data: {{ dossiers_list }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "date_creation",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
        search: false,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showRefresh: false,
        showFooter: false,
        showLoading: false,
        showExport: false,
        showMultiSort: false,
        showPaginationSwitch: false,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: false,
        detailView: true,
        detailFormatter: detailFormatter,
        columns: [
            {
                title: "Actions",
                align: "center",
                formatter: actionsFormatter,
                width: "130"
            }/*,
            {
                field: 'id',
                title: "{{ trans['Numéro'] }}",
            }*/,
            {
                field: 'date_creation',
                title: "{{ trans['Date'] }}",
                sortable: true,
            },
            {
                field: 'medecin',
                title: "{{ trans['Médecin'] }}",
            },
            {
                field: 'motif',
                title: "{{ trans['Motif'] }}",
            }
        ]
    });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>