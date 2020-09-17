<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Users Accounts'] }} <small> {{ trans['Manage profils and grants'] }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</a></li>
            <li><i class="fa fa-gear"></i> {{ trans['Settings'] }}</li>
            <li class="active">{{ trans['Users Accounts'] }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
         

        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active">
                      <h3 class="widget-user-username">{{ trans['Users list'] }}</h3>
                        <div class="text-right">
                            <div class="btn btn-default createPopup" data-toggle="modal" data-target="#createUser" style="margin-bottom: 10px;">{{ trans['Create a new user'] }}
                            </div>
                        </div>
                    </div>
                    <div class="widget-user-image">
                      <img class="img-circle" src="{{ url('img/user.jpg') }}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                      <div class="row">
                        <div class="box-body">
                            <div id="toolbar">
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
                      <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <div id="createUser" class="modal largemodal fade" role="dialog"></div>
    <div id="editUser" class="modal largemodal fade" role="dialog"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index) {
        return '<a class="editPopup" title="{{ trans["Modifier"] }}" href="#" data-toggle="modal" data-target="#editUser" data-user="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["Supprimer"] }}" href="#" data-User="' + value + '" data-username="' + row.nom + " " + row.prenom + '"><i class="glyphicon glyphicon-remove"></i></a>';
    }
    function profileFormatter(value, row, index) {
        var rs = '';
        if(value == 'agent'){
            rs = 'agent (Autre profile)';
        }
        else{
            rs = value;
        }
        return '<span class="label label-primary label-lg">' + rs + '</span>';
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
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        $('body').on('click', '.createPopup', function () {
            $.ajax({
                url: "{{url('user/createUser')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createUser').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('user/editUser')}}/" + $(this).data('user'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editUser').html(html);
            });
        });

        $('body').on('click', '.showChambre', function () {
            $.ajax({
                url: "{{url('user/showchambre')}}/" + $(this).data('chambre'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#showChambre').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var user_id = $(this).data('user');
             var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans["Cette action supprimera l'utilisateur nommé:"] }} " + $(this).data('username'),
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
                            url: "{{url('user/deleteUser')}}/" + user_id,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '{{ trans["Deleted!"] }}',
                                    "{{ trans["L'utilisateur a été supprimé avec succès."] }}",
                                    'success'
                                );
                                $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
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

        $('#table-javascript').bootstrapTable({
            data: {{ users }},
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
            //showRefresh: false,
            //showToggle: true,
            showFooter: false,
            showLoading: true,
            showExport: true,
            showPaginationSwitch: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            exportDataType : "selected",
            mobileResponsive: true,
            filterControl: true,
            showColumns: true,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'nom',
                    title: "{{ trans['Nom'] }}",
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
                    field: 'email',
                    title: "{{ trans['Email'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'telephone',
                    title: "{{ trans['Téléphone'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'service_name',
                    title: "{{ trans['Service'] }}",
                    align: "center",
                }
                ,
                {
                    field: 'profile',
                    title: "{{ trans['Profil'] }}",
                    align: "center",
                    formatter: profileFormatter,
                },
                {
                    field: 'id',
                    title: "Actions",
                    align: "center",
                    width: "100px",
                    formatter: actionsFormatter,
                }
            ]
        });

        // Function located in opsise.js for modal form processing
        submitAjaxForm();

        $("[data-mask]").inputmask();
    });
</script>