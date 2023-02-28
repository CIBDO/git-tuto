<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Opérations financières'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Finances'] ?></a></li>
            <li class="active"><i class="fa fa-circl-o"></i> <?= $trans['Gestion'] ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1"><?= $trans['Du '] ?> :</label>
                                        <?= $this->tag->datefield(['date1', 'class' => 'form-control', 'id' => 'date1']) ?>
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2"><?= $trans['Au '] ?> :</label>
                                    <?= $this->tag->datefield(['date2', 'class' => 'form-control', 'id' => 'date2']) ?>
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation  pull-left" title="<?= $trans['Recherche'] ?>">
                                        <i class="fa fa-fw fa-filter"></i> <?= $trans['Filtrer'] ?>
                                    </button>
                                </div>

                            </div>  

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" >
            <?php if (($userId == 1) || in_array('f_w', $userPermissions) || in_array('f_a', $userPermissions)) { ?>
                <button type="button" class="btn btn-primary pull-right createPopup" data-toggle="modal" data-target="#createFOperation">
                    <?= $trans['Enregistrer une opération'] ?>
                </button>
            <?php } ?>
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

    <div id="createFOperation" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    <div id="editFOperation" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
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
        return '<a class="btn btn-primary btn-xs col-xs-12 editPopup" title="<?= $trans['Modifier un FOperation'] ?>" href="#" data-toggle="modal" data-target="#editFOperation" data-typeassurance="' + value + '"><i class="fa fa-search pull-left"></i><span class="pull-right">voir</a>';
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
            $('body').addClass('loading');
            $.ajax({
                url: "<?= $this->url->get('f_operation/createFOperation') ?>",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createFOperation').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "<?= $this->url->get('f_operation/editFOperation') ?>/" + $(this).data('typeassurance'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editFOperation').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var foperation_id = $(this).data('typeassurance');
            var currentTr = $(this).closest("tr");
            swal(
                    {
                        title: '<?= $trans['Etes vous sûre?'] ?>',
                        text: "<?= $trans['Cette action va supprimer l\'opération'] ?> ",
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
                    },
                    function(isConfirm) {
                        if (isConfirm === true) {
                            $('body').addClass('loading');
                            $.ajax({
                                url: "<?= $this->url->get('f_operation/deleteFOperation') ?>/" + foperation_id,
                                cache: false,
                                async: true
                            })
                            .done(function( result ) {
                                console.log(result);
                                $('body').removeClass('loading');
                                if(result == "1"){
                                    swal(
                                        '<?= $trans['Supprimé!'] ?>',
                                        '<?= $trans[' Le FOperation a été supprimé avec succès.'] ?>',
                                        'success'
                                    );
                                    $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                                    $('body').addClass('loading');
                                    window.location.reload();
                                }
                                else{
                                    swal(
                                        '<?= $trans['Annulé!'] ?>',
                                        "<?= $trans['Annulé'] ?>",
                                        'error'
                                    );
                                }
                            });
                        } else if (isConfirm === false) {
                            
                        } else {
                        // Esc, close button or outside click
                        // isConfirm is undefined
                        }
                    }
            );
        });

        function typeCompteFormatter(value, row){
            if(value == "depense"){
                return "Dépense";
            }
            return "Récette";
        }

        $('#table-javascript').bootstrapTable({
        data: <?= $foperation ?>,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        /*sortOrder: "desc",
        sortName: "id",*/
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showLoading: true,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        showColumns: true,
        filterControl: true,
        rowStyle: rowStyle,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'date',
                title: "<?= $trans['Date'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'agent_nom',
                title: "<?= $trans['Enregistrée par'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'nature',
                title: "<?= $trans['Nature'] ?>",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'type_compte',
                title: "<?= $trans['Type de compte'] ?>",
                sortable: true,
                formatter: typeCompteFormatter
            },
            {
                field: 'compte_numero',
                title: "<?= $trans['Compte'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'compte_libelle',
                title: "<?= $trans['Libellé du compte'] ?>",
                sortable: true,
            },
            {
                field: 'montant',
                title: "<?= $trans['Montant'] ?>",
                sortable: true,
                align: 'right',
                formatter: amountFormatter,
            },
            {
                field: 'compte',
                title: "<?= $trans['Compte Bancaire'] ?>",
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
        $('#table-javascript').bootstrapTable('hideColumn', 'agent_nom');

        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>