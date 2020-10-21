<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Gestion des formulaires ASC'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Formulaires'] }}</a></li>
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
                            <button class="btn btn-info createFormulaires" data-toggle="modal"
                                    data-target="#createFormulaires"><i class="fa fa-plus"></i> Ajouter
                            </button>
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
                                    <tr {% if currentForms_id == f.id %} class="success" {% endif %}>
                                        <td>
                                            <a href="{{ url('formulaires_asc/index') }}/{{ f.id }}"
                                               class="ajax-navigation">
                                                {{ f.title }}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="editFormulaires" title="{{ trans["Editer"] }}" href="#"
                                               data-toggle="modal" data-target="#editFormulaires"
                                               data-forms="{{ f.id }}"><i class="glyphicon glyphicon-edit"></i></a>
                                            &nbsp;&nbsp;
                                            <a class="deleteBtn" title="{{ trans["Supprimer"] }}" href="#"
                                               data-forms="{{ f.id }}" data-formsname="{{ f.title }}"><i
                                                        class="glyphicon glyphicon-remove"></i></a>
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

                <div class="col-xs-8" id="elemDiv">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ currentForms.title }}</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <a style="margin-top: 5px" class="btn btn-primary"
                               href="{{ config.application.baseUri }}files/xls/{{ currentForms.formid }}.xls"
                               download="{{ currentForms.title }}.xls">Exporter les données</a>
                            <a style="margin-top: 5px" class="btn btn-primary ajax-navigation" href="/formulaires_asc/updatecsv/csv_suivi">
                                <i class="fa fa-arrow-circle-o-up"></i>
                                Mettre à jour csv_suivi.csv
                            </a>
                            <a style="margin-top: 5px" class="btn btn-primary ajax-navigation" href="/formulaires_asc/updatecsv/liste_asc">
                                <i class="fa fa-arrow-circle-o-up"></i>
                                Mettre à jour liste_asc.csv
                            </a>
                            <a style="margin-top: 5px" class="btn btn-primary ajax-navigation" href="/formulaires_asc/updatecsv/liste_patient">
                                <i class="fa fa-arrow-circle-o-up"></i>
                                Mettre à jour liste_patient.csv
                            </a>
                            <a style="margin-top: 5px" class="btn btn-primary ajax-navigation" href="/formulaires_asc/updatecsv/localite_liste">
                                <i class="fa fa-arrow-circle-o-up"></i>
                                Mettre à jour localite_liste.csv
                            </a>
                        </div>

                    </div>
                </div>

            {% endif %}
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
        if (data != "c") {
            cas_c = 'style="visibility: hidden;"';
        }
        text = '<li>' +
            '<span class="handle">' +
            '<i class="fa fa-ellipsis-v"></i>' +
            '<i class="fa fa-ellipsis-v"></i>' +
            '</span>' +
            '<span class="text">' +
            '<input type="hidden" name="element[]" value="" />' +
            '<input type="hidden" name="type_valeur[]" value="' + data + '" />' +
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

    $(document).ready(function () {

        $(".todo-list").sortable({
            placeholder: "sort-highlight",
            handle: ".handle",
            forcePlaceholderSize: true,
            zIndex: 999999
        });

        $('body').on('click', '.suppElement', function () {
            var current = $(this).closest("li");
            $(current).css('background-color', '#ff9933').fadeOut(300, function () {
                $(this).remove();
            });
        });

        $('body').on('click', '.suppElementHard', function () {
            var id = $(this).data("id");
            var current = $(this).closest("li");
            $.ajax({
                url: "{{ url('formulaires/deleteItem/') }}" + id,
                cache: false,
                async: true
            })
                .done(function (html) {
                    if (html == "1") {
                        $(current).css('background-color', '#ff9933').fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                });
        });

        $('body').on('click', '.createFormulaires', function () {
            $.ajax({
                url: "{{ url('formulaires_asc/createFormulaires') }}",
                cache: false,
                async: true
            })
                .done(function (html) {
                    $('#createFormulaires').html(html);
                });
        });

        $('body').on('submit', '#f_formsElements', function () {
            $('body').addClass('loading');
        });

        $('body').on('click', '.editFormulaires', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{ url('formulaires_asc/editFormulaires') }}/" + $(this).data('forms'),
                cache: false,
                async: true
            })
                .done(function (html) {
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
                .then(function () {
                    $('body').addClass('loading');
                    $.ajax({
                        url: "{{ url('formulaires_asc/deleteFormulaires') }}/" + forms_id,
                        cache: false,
                        async: true
                    })
                        .done(function (result) {
                            console.log(result);
                            $('body').removeClass('loading');
                            if (result == "1") {
                                swal(
                                    '{{ trans["Deleted!"] }}',
                                    '{{ trans["Le formulaires a été supprimé avec succès."] }}',
                                    'success'
                                );
                                $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function () {
                                    $(this).remove();
                                });
                                $('#elemDiv, #typeDiv').remove();
                                /*window.location.reload();*/
                            } else {
                                swal(
                                    '{{ trans["Cancelled!"] }}',
                                    "{{ trans['Cancelled'] }}",
                                    'error'
                                );
                            }
                        });
                }, function (dismiss) {
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