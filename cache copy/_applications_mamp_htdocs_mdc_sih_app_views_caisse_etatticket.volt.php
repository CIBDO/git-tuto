<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Tickets vendus'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Caisse'] ?></a></li>
            <li class="active"><i class="fa fa-circl-o"></i> <?= $trans['Tickets vendus'] ?></li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1"><?= $trans['Du '] ?> :</label>
                                        <?= $this->tag->datefield(['date1', 'class' => 'form-control', 'id' => 'date1']) ?>
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2"><?= $trans['Au '] ?> :</label>
                                    <?= $this->tag->datefield(['date2', 'class' => 'form-control', 'id' => 'date2']) ?>
                                </div>

                                <div class="form-group  col-md-2" style="margin-right : 10px">
                                    <label for="etat"><?= $trans['Etat'] ?> :</label>
                                    <?= $this->tag->select(['etat', ['1' => 'Validé', '0' => 'Annulé'], 'useEmpty' => false, 'class' => 'form-control', 'required' => 'required']) ?>
                                </div>

                                <div class="form-group  col-md-3">
                                    <button type="submit" class="btn btn-defaultx  ajax-navigation" title="<?= $trans['Recherche'] ?>">
                                        <i class="fa fa-filter"></i> <?= $trans['Filtrer'] ?>
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
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

    <div id="createAjustement" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(new Date(value).getTime()/1000, "X").format("<?= $trans['js_date_format'] ?>");
        } else {
            return "-";
        }
    }

    function idFormatter(value, row, index) {
        return '<a class="btn btn-primary btn-xs col-xs-12 " href="<?= $this->url->get('print/ticket/') ?>' + value + '"><i class="fa fa-search pull-left"></i><span class="pull-right">' + value + '</span></a>';
    }
    function cancelFormatter(value, row, index) {
        if(row.etat == 1){
            return '<a class="btn btn-warning btn-xs cancelTicket" data-ticketid="' + row.id + '"><i class="fa fa-trash pull-left"></i>Annuler</a>';
        }
        else{
            return "";
        }
    }

    function montant_patientFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_patient, 10);
        }
        return numberFormatter(total);
    }
    function montant_normalFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_normal, 10);
        }
        return numberFormatter(total);
    }
    function montant_differenceCelyFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_difference, 10);
        }
        return numberFormatter(total);
    }
    function montant_restantFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].montant_restant, 10);
        }
        return numberFormatter(total);
    }

    $('body').on('click', '.cancelTicket', function () {
        var ticketid = $(this).data('ticketid');
        var currentTr = $(this).closest("tr");
        swal(
            {
                title: '<?= $trans['Etes vous sûr?'] ?>',
                text: "<?= $trans['Cette action annulera le ticket N°:'] ?> " + $(this).data('ticketid') + "",
                html: "Veuillez saisir le motif d'annulation:<br><input class='form-control' type='text' name='motif_annulation' id='motif_annulation' />",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= $trans['Oui, Annuler!'] ?>',
                cancelButtonText: '<?= $trans['Non, je renonce!'] ?>',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                closeOnConfirm: false,
                closeOnCancel: true
             })
            .then(function() {
                
                if($("#motif_annulation").val() == ""){
                    alert("Veuillez saisir le motif d'annulation.");
                    return;
                }
                $('body').addClass('loading');
                $.ajax({
                    url: "<?= $this->url->get('caisse/cancelTicket') ?>/" + ticketid + "/" + $("#motif_annulation").val(),
                    cache: false,
                    async: true
                })
                .done(function( result ) {
                    console.log(result);
                    $('body').removeClass('loading');
                    if(result == "1"){
                        swal(
                            '<?= $trans['Opération effectuée!'] ?>',
                            '<?= $trans['Le ticket a été annulé avec succès.'] ?>',
                            'success'
                        );
                        $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                        /*$('body').addClass('loading');
                        window.location.reload();*/
                    }
                    else{
                        swal(
                            '<?= $trans['Un probleme est survenu'] ?>',
                            "<?= $trans['veuillez contacter un administrateur'] ?>",
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

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });


        $('#table-javascript').bootstrapTable({
            data: <?= $tickets ?>,
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
            showLoading: true,
            showExport: true,
            showMultiSort: true,
            showPaginationSwitch: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            exportDataType : "selected",
            mobileResponsive: true,
            filterControl: true,
            showColumns: true,
            showFooter: true,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'id',
                    title: "<?= $trans['N° Ticket'] ?>",
                    sortable: true,
                    filterControl: "input",
                    formatter: idFormatter,
                },
                {
                    field: 'date',
                    title: "<?= $trans['Date  de création'] ?>",
                    sortable: true,
                    formatter:date2Formatter
                },
                {
                    field: 'caissier_nom',
                    title: "<?= $trans['Caissier'] ?>",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'patient_id',
                    title: "<?= $trans['ID Patient'] ?>",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'patients_nom',
                    title: "<?= $trans['Patient'] ?>",
                    sortable: true,
                    filterControl: "input",
                    footerFormatter: function(){return 'Total';}
                },
                {
                    field: 'montant_normal',
                    title: "<?= $trans['Total'] ?>",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_normalFooter
                },
                {
                    field: 'montant_difference',
                    title: "<?= $trans['Difference CELY'] ?>",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_differenceCelyFooter
                },
                {
                    field: 'montant_patient',
                    title: "<?= $trans['Montant <br>Encaissé'] ?>",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                    footerFormatter: montant_patientFooter
                },
                {
                    field: 'montant_restant',
                    title: "<?= $trans['Montant Prise<br> en charge'] ?>",
                    sortable: true,
                    filterControl: "input",
                    align: 'right',
                    formatter: amountFormatter,
                    footerFormatter: montant_restantFooter
                },
                {
                    field: 'assurance_libelle',
                    title: "<?= $trans['Tierc payant'] ?>",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'assurance_taux',
                    title: "<?= $trans['Taux'] ?>",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'motif_annulation',
                    title: "<?= $trans['Motif Annulation'] ?>",
                    sortable: true,
                    filterControl: "input"
                },
            <?php if (($userId == 1) || in_array('caisse_a', $userPermissions)) { ?>
                {
                    title: "<?= $trans['Annuler <br>le ticket'] ?>",
                    sortable: true,
                    formatter: cancelFormatter,
                }
            <?php } ?>
            ]
        });
        
        $('#table-javascript').bootstrapTable('hideColumn', 'motif_annulation');

    
    });
</script>