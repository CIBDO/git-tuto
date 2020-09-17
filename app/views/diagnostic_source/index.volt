<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Diagnostic de consultation"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Diagnostic de consultation"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
         <div class="row">
            <div class="col-xs-12" >
               {% if (userId == 1) OR in_array("cs_w", userPermissions) OR in_array("cs_a", userPermissions) %}
                <button type="button" class="btn btn-primary pull-right createPopup"  data-toggle="modal" data-target="#createDiagnosticSource">
                    {{trans['Créer un motif']}}
                </button>
                {% endif %}
                                
            </div>
        </div>

        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <div class="alert alert-warning alert-dismissable">
                            <h4><i class="icon fa fa-warning"></i> Pour accéder à cette liste dans le formulaire de consultation, assurez d'avoir choisi "Autre" comme option "SOurce de données diagnostic".</h4>
                        </div>
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
            </div>
        </div>
        
    </section>

    <div id="createDiagnosticSource" class="modal fade" role="dialog"></div>
    <div id="editDiagnosticSource" class="modal fade" role="dialog"></div>
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
        return '<a class="editPopup" title="{{ trans["edit DiagnosticSource"] }}" href="#" data-toggle="modal" data-target="#editDiagnosticSource" data-diagnosticsource="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="{{ trans["delete DiagnosticSource"] }}" href="#" data-DiagnosticSource="' + value + '" data-diagnosticsourcename="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
    }
    function chambreFormatter(value, row, index) {
        return ['-'].join('');
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
                url: "{{url('diagnostic_source/createDiagnosticSource')}}",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createDiagnosticSource').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "{{url('diagnostic_source/editDiagnosticSource')}}/" + $(this).data('diagnosticsource'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editDiagnosticSource').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var diagnosticsourceid = $(this).data('diagnosticsource');
             var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '{{ trans["Are you sure?"] }}',
                    text: "{{ trans['Cette action supprimera le motif nommé:'] }} " + $(this).data('diagnosticsourcename'),
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
                        url: "{{url('diagnostic_source/deleteDiagnosticSource')}}/" + diagnosticsourceid,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '{{ trans["Deleted!"] }}',
                                '{{ trans["Le motif a été supprimé avec succès."] }}',
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
        data: {{ diagnosticSource }},
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
        showRefresh: false,
        showFooter: false,
        showLoading: true,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: true,
        rowStyle: rowStyle,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'libelle',
                title: "{{ trans['Libellé'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'id',
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