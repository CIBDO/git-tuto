<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Liste des ASC'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans['Consultation'] }}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Liste ASC'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                {% if (userId == 1) OR in_array("dp_w", userPermissions) OR in_array("dp_a", userPermissions) %}
                    <a type="button" class="btn btn-primary pull-right createPopup" href="{{ url('asc/form') }}">
                        <i class="fa fa-plus"></i> {{ trans['Créer un ASC'] }}
                    </a>
                {% endif %}
            </div>
        </div>


        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <div id="toolbar">
                        </div>

                        <div class="content">
                            <div class="table-responsive">
                                <table id="table-javascript">
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

    </section>

    <div id="createSthing" class="modal fade" role="dialog"></div>
</div>

<script>
    $('body').on('click', '.supElm', function () {
        var id = $(this).data('id');
        var currentTr = $(this).closest("tr");
        swal(
            {
                title: '{{ trans["Are you sure?"] }}',
                text: "{{ trans['Cette action supprimera ASC nommé:'] }} " + $(this).data('name'),
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
                    url: "{{ url('asc/deleteAsc') }}/" + id,
                    cache: false,
                    async: true
                })
                    .done(function (result) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if (result == "1") {
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["ASC supprimé avec succès."] }}',
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

    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function ticketFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs" title="{{ trans["Details"] }}" href="{{ url("caisse/index") }}/' + row.id + '"><i class="fa fa-plus"></i>&nbsp; {{ trans["Ticket"] }}</a>';
    }

    function pharmacieFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs" title="{{ trans["Details"] }}" href="{{ url("caisse_pharmacie/index") }}/' + row.id + '"><i class="fa fa-plus"></i>&nbsp; {{ trans["Pharmacie"] }}</a>';
    }

    function actionsFormatter(value, row, index) {
        return '<a class="" title="Modifier" href="/asc/form/' + row.id + '" data-id="' + row.id + '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;' +
            '<a class="supElm" title="Supprimer" href="#" data-id="' + row.id + '" data-name="' + row.prenom + ' ' + row.nom + '"><i class="fa fa-trash"></i></a>';
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

    $(document).ready(function () {

        var width = "200px"; //Width for the select inputs
        var select2Residence = $("#residence_id").select2({
            width: width,
            placeholder: 'Selectionnez',
            allowClear: true,
            theme: "classic"
        });
        $("#_sexe").select2({width: width, allowClear: true, placeholder: "choisir", theme: "classic"});
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function () {
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{ url('services/editService') }}/" + $(this).data('service'),
                cache: false,
                async: true
            })
                .done(function (html) {
                    $('body').removeClass('loading');
                    $('#editService').html(html);
                });
        });
        var _data = {{ ascs }};
        $('#table-javascript').bootstrapTable({
            data: _data,
            cache: false,
            striped: true,
            pagination: true,
            pageSize: 10,
            pageList: [10, 25, 50, 100, 200],
            sortOrder: "desc",
            sortName: "id",
            locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
            search: true,
            minimumCountColumns: 2,
            clickToSelect: false,
            toolbar: "#toolbar",
            showFooter: false,
            showLoading: true,
            showExport: true,
            showMultiSort: false,
            showPaginationSwitch: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            exportDataType: "selected",
            mobileResponsive: true,
            showColumns: true,
            filterControl: true,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },{
                    field: 'code_asc',
                    title: "{{ trans['Code ASC'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'prenom',
                    title: "{{ trans['Prénom'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'nom',
                    title: "{{ trans['Nom'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'residence',
                    title: "{{ trans['localité'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'telephone',
                    title: "{{ trans['Téléphone'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'profession',
                    title: "{{ trans['Proféssion'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                /*{% if (userId == 1) OR in_array("venteticket_w", userPermissions) OR in_array("caisse_a", userPermissions) %}
            {
                title: "Ticket",
                align: "center",
                formatter: ticketFormatter,
            },
        {% endif %}
        {% if (userId == 1) OR in_array("ventemedic_w", userPermissions) OR in_array("ph_a", userPermissions) %}
            {
                title: "Pharmacie",
                align: "center",
                formatter: pharmacieFormatter,
            },
        {% endif %}*/
                {
                    title: "Actions",
                    align: "center",
                    formatter: actionsFormatter,
                }
            ]
        });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>