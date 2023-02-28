<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Residence'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Residence'] ?></a></li>
            <li class="active"><i class="fa fa-circl-o"></i> <?= $trans['Gestion'] ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
         <div class="row">
            <div class="col-xs-12" >
                
                <button type="button" class="btn btn-primary pull-right createPopup" data-toggle="modal" data-target="#createResidence">
                    <?= $trans['Créer une residence'] ?>
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

    <div id="createResidence" class="modal fade" role="dialog"></div>
    <div id="editResidence" class="modal fade" role="dialog"></div>
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
        return '<a class="editPopup" title="<?= $trans['edit Residence'] ?>" href="#" data-toggle="modal" data-target="#editResidence" data-typeassurance="' + value + '"><i class="glyphicon glyphicon-edit"></i></a>' +
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class="deleteBtn" title="<?= $trans['delete Residence'] ?>" href="#" data-typeassurance="' + value + '" data-residencename="' + row.libelle + '"><i class="glyphicon glyphicon-remove"></i></a>';
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
                url: "<?= $this->url->get('residence/createResidence') ?>",
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createResidence').html(html);
            });
        });

        $('body').on('click', '.editPopup', function () {
            $('body').addClass('loading');
            $.ajax({
                url: "<?= $this->url->get('residence/editResidence') ?>/" + $(this).data('typeassurance'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#editResidence').html(html);
            });
        });

        $('body').on('click', '.deleteBtn', function () {
            var residence_id = $(this).data('typeassurance');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '<?= $trans['Are you sure?'] ?>',
                    text: "<?= $trans['Cetta action supprimera la residence nommée:'] ?> " + $(this).data('residencename'),
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= $trans['Yes, delete it!'] ?>',
                    cancelButtonText: '<?= $trans['No, cancel!'] ?>',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: false,
                    closeOnCancel: true
                })
                .then(function() {
                        $('body').addClass('loading');
                        $.ajax({
                            url: "<?= $this->url->get('residence/deleteResidence') ?>/" + residence_id,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '<?= $trans['Deleted!'] ?>',
                                    '<?= $trans['La residence a été supprimée avec succès.'] ?>',
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
        data: <?= $residence ?>,
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
                title: "<?= $trans['Libellé'] ?>",
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