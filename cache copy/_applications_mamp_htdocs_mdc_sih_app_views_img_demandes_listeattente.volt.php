<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Imagerie - Liste d\'attente'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Imagerie'] ?></a></li>
            <li class="active"><i class="fa fa-circl-o"></i> <?= $trans['liste d\'attente'] ?></li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content">
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

    <div id="createSthing" class="modal fade" role="dialog"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("<?= $trans['js_date_format'] ?>");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index){
        return '<a class="btn btn-info btn-xs " href=\'<?= $this->url->get('img_demandes/dossier/') ?>'+row.patient_id+'\'"><i class="fa fa-fw fa-clone"></i> <?= $trans['Détails'] ?></a>';
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

        var _data = <?= $liste_attentes ?>;
        $('#table-javascript').bootstrapTable({
        data: _data,
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "asc",
        sortName: "date",
        locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showRefresh: false,
        showFooter: false,
        showLoading: true,
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
                field: 'date',
                title: "<?= $trans['Date et heure'] ?>",
                sortable: true,
                formatter: dateFormatter,
            },
            {
                field: 'prestation_id',
                title: "<?= $trans['Ticket'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'patient_nom',
                title: "<?= $trans['Nom Patient'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'patient_telephone',
                title: "<?= $trans['Téléphone Patient'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'patient_adresse',
                title: "<?= $trans['Adresse Patient'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'medecin_traiteur',
                title: "<?= $trans['Medecin Traiteur'] ?>",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'acte',
                title: "<?= $trans['Prestation'] ?>",
                sortable: true,
                filterControl: "input"
            },
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