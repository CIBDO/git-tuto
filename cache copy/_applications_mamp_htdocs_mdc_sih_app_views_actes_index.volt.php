<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Prestations'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Prestations'] ?></a></li>
            <li class="active"><i class="fa fa-circl-o"></i> <?= $trans['Gestion'] ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
         <div class="row">
            <div class="col-xs-12" >

                <button type="button" class="btn btn-primary pull-right createPopup" data-toggle="modal" data-target="#createActe">
                    <?= $trans['Créer une prestation'] ?>
                </button>
                                
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

    <div id="createActe" class="modal fade" role="dialog"></div>
    <div id="editActe" class="modal fade" role="dialog"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("<?= $trans['js_date_format'] ?>");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index) {
        return '<a class="editPopup" title="<?= $trans['edit Acte'] ?>" href="#" data-toggle="modal" data-target="#editActe" data-acte="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="<?= $trans['delete Acte'] ?>" href="#" data-Acte="' + value + '" data-actename="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
    }
    function chambreFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs showMerchant" title="<?= $trans['Details'] ?>" href="#" data-toggle="modal" data-target="#showMerchant" data-merchants="' + value + '"><i class="fa fa-search"></i>&nbsp; 3 &nbsp;<?= $trans['Details'] ?></a>';
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
            $('#createActe').html("");
            $.ajax({
                url: "<?= $this->url->get('actes/createActe') ?>",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createActe').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('#editActe').html("");
            $('body').addClass('loading');
            $.ajax({
                url: "<?= $this->url->get('actes/editActe') ?>/" + $(this).data('acte'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editActe').html(html);
            });
        });

        $('body').on('click', '.showChambre', function () {
            $.ajax({
                url: "<?= $this->url->get('actes/showchambre') ?>/" + $(this).data('chambre'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#showChambre').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var acte_id = $(this).data('acte');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '<?= $trans['Etes vous sure?'] ?>',
                    text: "<?= $trans['Cette action supprimera la prestation nommé:'] ?> " + $(this).data('actename'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= $trans['Oui, supprimer!'] ?>',
                    cancelButtonText: '<?= $trans['Non, annuler!'] ?>',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: false,
                    closeOnCancel: true
                })
                .then(function() {
                    $('body').addClass('loading');
                    $.ajax({
                        url: "<?= $this->url->get('actes/deleteActe') ?>/" + acte_id,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        $('body').removeClass('loading');
                        if(result == "1"){
                            swal(
                                '<?= $trans['Supprimé!'] ?>',
                                '<?= $trans['La prestation a été supprimée avec succès.'] ?>',
                                'success'
                            );
                            $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                            $('body').addClass('loading');
                            window.location.reload();
                        }
                        else{
                            swal(
                                '<?= $trans['Cancelled!'] ?>',
                                "<?= $trans['Cancelled'] ?>",
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
        data: <?= $actes ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "id",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showRefresh: false,
        showFooter: false,
        showLoading: true,
        showColumns: true,
        showExport: true,
        showMultiSort: true,
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
                field: 'code',
                title: "<?= $trans['Code'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'libelle',
                title: "<?= $trans['Libellé'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'unite',
                title: "<?= $trans['Unité'] ?>",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'type',
                title: "<?= $trans['Type'] ?>",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'service_name',
                title: "<?= $trans['Service'] ?>",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'prix',
                title: "<?= $trans['Prix'] ?>",
                sortable: true,
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