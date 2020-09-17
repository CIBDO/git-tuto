<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Gestion des formulaires de consultation'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Formulaires']}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
    
    <!-- Main content -->
    <section class="content">

        <!-- Main row -->
        <div class="row">
            <div class="col-xs-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Liste des formulaires</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-info createFormulaires" data-toggle="modal" data-target="#createFormulaires"><i class="fa fa-plus"></i> Ajouter</button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="table-responsive">
                        <table class="table no-margin">
                          <thead>
                            <tr>
                              <th>Nom</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            {% for index, f in forms %}
                            <tr {% if currentForms_id == f['id'] %} class="success" {% endif %}>
                                <td>
                                    <a href="{{ url('formulaires/index') }}/{{ f['id'] }}" class="ajax-navigation">
                                        {{ f['libelle'] }} ({{ f['type'] }})
                                    </a>
                                </td>
                                <td>
                                    <a class="editFormulaires" title="{{ trans["Editer"] }}" href="#" data-toggle="modal" data-target="#editFormulaires" data-forms="{{ f['id'] }}"><i class="glyphicon glyphicon-edit"></i></a>
                                    &nbsp;&nbsp;
                                    <a class="deleteBtn" title="{{ trans["Supprimer"] }}" href="#" data-forms="{{ f['id']}}" data-formsname="{{ f['libelle']}}"><i class="glyphicon glyphicon-remove"></i></a>
                                </td>
                            </tr>
                            {% endfor %}
                          </tbody>
                        </table>
                      </div><!-- /.table-responsive -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            {% if currentForms is defined %}

            <div class="col-xs-6" id="elemDiv">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Elements du formulaire</h3>
                    </div><!-- /.box-header -->
                    <form action="{{ url('formulaires/elements/' ~ currentForms_id) }}" id="f_formsElements" class="form" method="post">
                        <div class="box-body" id="elemBody" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <ul class="todo-list ui-sortable" id="elemList">
                            {% for index, elem in formsElements %}
                                <li> 
                                    <span class="handle"> 
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <span class="text">
                                        <input type="hidden" name="element[]" value="{{ elem.id }}" />
                                        <input type="hidden" name="type_valeur[]" value="{{ elem.type_valeur }}" />
                                        Champs de type 
                                        {% if elem.type_valeur == 'n' %}
                                            numerique
                                        {% endif %}
                                        {% if elem.type_valeur == 'an' %}
                                            alphanumerique
                                        {% endif %}
                                        {% if elem.type_valeur == 'd' %}
                                            date
                                        {% endif %}
                                        {% if elem.type_valeur == 'c' %}
                                            liste de choix
                                        {% endif %}

                                        <input type="text" name="libelle[]" class="form-control input-sm" value="{{ elem.libelle }}" required="required" placeholder="Veuillez saisir le libellé" /> 

                                        {% if elem.type_valeur == 'c' %}

                                            Valeurs possibles <i>(Pour les champs de type "liste de choix")</i>:
                                            <br /> <input type="text" class="form-control  input-sm" name="valeur_possible[]" value="{{ elem.valeur_possible }}" required="required" placeholder="Valeurs possibles (séparée par des virgules)" />

                                        {% else %}

                                            <input type="hidden" name="valeur_possible[]" value="{{ elem.valeur_possible }}" />

                                        {% endif %}
                                        <br /> 
                                        <select class="form-control input-sm" name="required[]">
                                            <option value="0" {% if elem.required == '0' %}selected{% endif %}>NON REQUIS</option>
                                            <option value="1" {% if elem.required == '1' %}selected{% endif %}>REQUIS</option>
                                        </select>

                                        <br>
                                        <select name="place_after_c[]" class="form-control input-sm">
                                            <option value="-">-- Position dans formulaire --</option>
                                            <option {% if elem.place_after_c == 'motif' %}selected{% endif %} value="motif">Après motif</option>
                                            <option {% if elem.place_after_c == 'histoire' %}selected{% endif %} value="histoire">Après histoire</option>
                                            <option {% if elem.place_after_c == 'exam_clinic' %}selected{% endif %} value="exam_clinic">exam_clinic</option>
                                            <option {% if elem.place_after_c == 'constante' %}selected{% endif %} value="constante">Après constante</option>
                                            <option {% if elem.place_after_c == 'prescription' %}selected{% endif %} value="prescription">Après prescription</option>
                                            <option {% if elem.place_after_c == 'exam_comp' %}selected{% endif %} value="exam_comp">Après exam_comp</option>
                                            <option {% if elem.place_after_c == 'diagnostic' %}selected{% endif %} value="diagnostic">Après diagnostic</option>
                                            <option {% if elem.place_after_c == 'hypothese' %}selected{% endif %} value="hypothese">Après hypothese</option>
                                            <option {% if elem.place_after_c == 'commentaire' %}selected{% endif %} value="commentaire">Après commentaire</option>
                                            <option {% if elem.place_after_c == 'resume' %}selected{% endif %} value="resume">Après resumé</option>
                                        </select>
                                    </span>
                                    <div class="tools">
                                        <i data-id="{{ elem.id }}" class="fa fa-trash-o suppElementHard"></i>
                                    </div>
                                </li>
                            {% endfor %}
                            </ul>
                            <br />
                        </div><!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-sm btn-info btn-flat pull-right">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xs-2"  id="typeDiv">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Elements disponible</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div id="n" style="cursor: pointer;" draggable="true" ondragstart="drag(event)">
                            numerique
                        </div>

                        <div id="an" style="cursor: pointer;" draggable="true" ondragstart="drag(event)">
                            alphanumerique
                        </div>

                        <div id="c" style="cursor: pointer;" draggable="true" ondragstart="drag(event)">
                            liste de choix
                        </div>

                        <div id="d" style="cursor: pointer;" draggable="true" ondragstart="drag(event)">
                            date
                        </div>

                    </div><!-- /.box-body -->
                </div>
            </div>

            {% endif  %}
        </div>
        
    </section>

    <div id="createFormulaires" class="modal fade" role="dialog"></div>
    <div id="editFormulaires" class="modal fade" role="dialog"></div>
</div>

<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var text = "";
        var cas_c = 'required="required"';
        if(data != "c"){
            cas_c = 'style="visibility: hidden;"';
        }
        text =  '<li>' + 
                    '<span class="handle">' + 
                        '<i class="fa fa-ellipsis-v"></i>' +
                        '<i class="fa fa-ellipsis-v"></i>' +
                    '</span>' +
                    '<span class="text">' +
                        '<input type="hidden" name="element[]" value="" />' +
                        '<input type="hidden" name="type_valeur[]" value="'+data+'" />' +
                        'Champs de type ' + $("#" + data).html() + '<br>' +
                        '<input type="text" name="libelle[]" value="" required="required" placeholder="Veuillez saisir le libellé" />' +
                        '<br /> <input ' + cas_c + ' type="text" class="form-control input-sm" name="valeur_possible[]" value="" placeholder="Valeurs possibles (séparée par des virgules)" />' +
                        '<br /> <select class="form-control input-sm" name="required[]"><option value="0">NON REQUIS</option><option value="1">REQUIS</option></select>' +
                    '</span>' +
                    '<div class="tools">' +
                        '<i class="fa fa-trash-o suppElement"></i>' +
                    '</div>' +
                '</li>';
        var target = ev.target;
        $("#elemBody #elemList").append(text);
    }

    $( document ).ready(function() {

        $(".todo-list").sortable({
          placeholder: "sort-highlight",
          handle: ".handle",
          forcePlaceholderSize: true,
          zIndex: 999999
        });

        $('body').on('click', '.suppElement', function () {
            var current = $(this).closest("li");
            $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
        });

        $('body').on('click', '.suppElementHard', function () {
            var id = $(this).data("id");
            var current = $(this).closest("li");
            $.ajax({
                url: "{{url('formulaires/deleteItem/')}}" + id,
                cache: false,
                async: true
            })
            .done(function( html ) {
                if(html == "1"){
                    $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
                }
            });
        });

        $('body').on('click', '.createFormulaires', function () {
            $.ajax({
                url: "{{url('formulaires/createFormulaires')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createFormulaires').html(html);
            });
        });

        $('body').on('submit', '#f_formsElements', function () {
            $('body').addClass('loading');
        });

        $('body').on('click', '.editFormulaires', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('formulaires/editFormulaires')}}/" + $(this).data('forms'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editFormulaires').html(html);
            });
        });


        $('body').on('click', '.deleteBtn', function () {
            var forms_id = $(this).data('forms');
             var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera le formulaire nommé:'] }} " + $(this).data('formsname'),
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
                        url: "{{url('formulaires/deleteFormulaires')}}/" + forms_id,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["Le formulaires a été supprimé avec succès."] }}',
                                'success'
                            );
                            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                            $('#elemDiv, #typeDiv').remove();
                            /*window.location.reload();*/
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