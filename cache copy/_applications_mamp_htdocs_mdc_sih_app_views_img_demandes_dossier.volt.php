<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $trans['Dossier d\'imagerie médicale'] ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $this->url->get('patients/index') ?>"><i class="fa fa-dashboard"></i> <?= $trans['Dossier patient'] ?></a></li>
            <li><i class="fa fa-circl-o"></i> <?= $trans['Dossier d\'imagerie médicale'] ?></li>
            <li class="active"><?= $patient->id ?></li>
        </ol>
    </section>

    <?php $this->flash->output() ?>

    <!-- Main content -->
    <section class="content connectedSortable">
        <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="<?= $this->url->get('img/user.jpg') ?>" alt="User profile picture">

                  <h3 class="profile-username text-center"><?= $patient->nom . ' ' . $patient->prenom ?></h3>

                  <p class="text-muted text-center"><?= $patient->profession ?></p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>Identfiant</b> <a class="pull-right"><?= $patient->id ?></a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-key margin-r-5"></i> <b>ID Technique</b> <a class="pull-right"><?= $patient->id_technique ?></a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-birthday-cake margin-r-5"></i> <b>Date de naissance</b> <a class="pull-right"><?= date('d/m/Y', strtotime($patient->date_naissance)) ?></a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Téléphone</b> <a class="pull-right"><?= $patient->telephone ?></a>
                    </li>
                    <li class="list-group-item">
                      <i class="fa fa-phone margin-r-5"></i> <b>Autre contact</b> <a class="pull-right"><?= $patient->telephone2 ?></a>
                    </li>

                    <li class="list-group-item">
                      <i class="fa fa-map-marker margin-r-5"></i> <b>Résidence</b> <a class="pull-right"><?= $patient_residence ?></a>
                      <br /><a> <?= $patient->adresse ?></a>
                    </li>
                  </ul>

                  <a href="<?= $this->url->get('patients/dossier/') ?><?= $patient->id ?>" class="btn btn-primary btn-block"><b>Voir le dossier complet</b></a>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">A propos </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-book margin-r-5"></i> Dernière visite</strong>
                  <a class="pull-right"><?php if (sizeof($patient->getImgDemandes()) > 0) { ?> <?= date('d/m/Y H:i:m', strtotime($patient->getImgDemandes(['order' => 'date Desc'])[0]->date)) ?> <?php } else { ?> ------- <?php } ?></a>

                  <hr>

                  <strong><i class="fa fa-warning margin-r-5"></i> Antécédents</strong>
                  <!-- <a class="btn btn-primary pull-right antecedantpop" data-antecedantid=""  tabindex="0" data-trigger="click" data-toggle="popover" title="Ajouter un antécédent" data-patientid="<?= $patient->id ?>">
                    <i class="glyphicon glyphicon-plus"></i>
                  </a> -->

                  <p id="antecedantlist">
                    <?php foreach ($patient_antecedant as $index => $antecedant) { ?>
                      <div>
                        <span class="label label-<?= $antecedant['niveau'] ?>">
                          <?= $antecedant['type'] ?> - <?= $antecedant['libelle'] ?>
                          <i class="glyphicon glyphicon-remove deleteBtnAntecedant"  data-antecedantid="<?= $antecedant['id'] ?>" data-antecedantname="<?= $antecedant['libelle'] ?>" title="Assurance" data-patientid="<?= $patient->id ?>"></i>
                        </span>
                      </div>
                    <?php } ?>
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Autres informations</strong>

                  <p><?= $patient->autre_infos ?></p>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#dossier_cons" data-toggle="tab" aria-expanded="false">Dossiers</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="dossier_cons">
                    <div class="row">
                      <div class="col-md-12">

                      <?php if (($userId == 1) || in_array('img_w', $userPermissions) || in_array('img_a', $userPermissions)) { ?>
                        <div class="row">
                          <div class="col-md-12">
                            <button type="button" class="btn createDemande btn-primary pull-right" data-toggle="modal" data-target="#createDemande" title="<?= $trans['Créer une demande'] ?>" data-patientid="<?= $patient->id ?>">
                                <?= $trans['Créer une demande'] ?>
                            </button>
                          </div>
                        </div>
                      <?php } ?>
                      
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
                <!-- /.tab-content -->
            </div>
              <!-- /.nav-tabs-custom -->
          </div>
            <!-- /.col -->
        </div>
        
    </section>

    <div id="createDemande" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>

<script>
  $(function () {
    $(".todo-list").sortable({
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999
    });

    $(".textarea").wysihtml5({
        toolbar: {
          "font-styles": false, // Font styling, e.g. h1, h2, etc.
          "emphasis": false, // Italics, bold, etc.
          "lists": false, // (Un)ordered lists, e.g. Bullets, Numbers.
          "html": false, // Button which allows you to edit the generated HTML.
          "link": false, // Button to insert a link.
          "image": false, // Button to insert an image.
          "color": false, // Button to change color of font
          "blockquote": false, // Blockquote
        }
      });
  });
</script>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("<?= $trans['js_date_format'] ?>");
        } else {
            return "-";
        } 
    }

    function actionsFormatter(value, row, index){
      var s = "";
      if(row.etat == "encours"){

        s += '<a href="<?= $this->url->get('img_demandes/editDossier2/') ?>' + row.patients_id + "/" + row.id + '" class="btn btn-xs btn-default ajax-navigation"><i class="fa fa-edit"></i> Résultat</a>&nbsp;&nbsp;';

        s += '<a href="<?= $this->url->get('print/imgDemande/') ?>' + row.id + '" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Imprimer</a>&nbsp;&nbsp;';
        
        <?php if (($userId == 1) || in_array('img_a', $userPermissions)) { ?>
          s += '<a class="btn btn-xs btn-info clotureBtn" title="<?= $trans['Clotûrer cette demande'] ?>" href="#" data-demandeid="' + row.id + '"><i class="fa fa-check"></i> Clotûrer</a>';
        <?php } ?>
      }

      if(row.etat == "création"){
        s += '<a href="<?= $this->url->get('img_demandes/editDossier2/') ?>' + row.patients_id + "/" +row.id + '" class="btn btn-xs btn-default ajax-navigation"><i class="fa fa-clone"></i> Résultat</a>&nbsp;&nbsp;';
      }
      if(row.etat == "clotûré"){
        s += '<a href="<?= $this->url->get('print/imgDemande/') ?>' + row.id + '" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Imprimer</a>&nbsp;&nbsp;';
      }
      
      return s;
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

      $(".antecedantpop").popover(
      {
          placement : 'left',
          html : true, 
          template: '<div class="popover" style="max-width:500px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Fermer</a></div></div>',
          content: function() {
                    var text = "";
                    $.ajax({
                        url: "<?= $this->url->get('img_demandes/antecedantPopover/') ?>" + $(this).data('patientid') + "/" + $(this).data('antecedantid'),
                        cache: false,
                        async: false
                    })
                    .done(function( html ) {
                        text = html;
                    });
                    return text;
                  },
          title: "Titre",
          animation: true,
          delay: { show: 100, hide: 100 },
      });
      $(document).on("click", ".popover-footer .btn" , function(){
        $(this).parents(".popover").popover('hide');
      });

        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs

        $('body').on('click', '.createDossier', function () {
            $.ajax({
                url: "<?= $this->url->get('img_demandes/createDossier') ?>/" + $(this).data('patientid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createDossier').html(html);
            });
        });

        $('body').on('click', '.createDemande', function () {
            $('#createDemande').html("");
            $('body').addClass('loading');
            $.ajax({
                url: "<?= $this->url->get('img_demandes/createDemande') ?>/" + $(this).data('patientid'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('body').removeClass('loading');
                $('#createDemande').html(html);
            });
        });
        

       
        $('body').on('click', '.suppElement', function () {
            var current = $(this).closest("li");
            $(current).css('background-color', '#ff9933').fadeOut(300, function(){ $(this).remove();});
        });

        $('body').on('click', '.deleteBtnAntecedant', function () {
            var id = $(this).data('antecedantid');
            var currentDiv = $(this).closest("div");
            swal(
                {
                    title: '<?= $trans['Etes vous sûre?'] ?>',
                    text: "<?= $trans['Cette action va supprimer l\'antécedent nommé:'] ?> '" + $(this).data('antecedantname') + "' des informations de ce patient",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= $trans['Oui, supprimer!'] ?>',
                    cancelButtonText: '<?= $trans['Non, annuler!'] ?>',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: true,
                    closeOnCancel: true
                })
                .then(function() {
                    $('body').addClass('loading');
                    $.ajax({
                        url: "<?= $this->url->get('img_demandes/deleteAntecedant') ?>/" + id,
                        cache: false,
                        async: true
                    })
                    .done(function( result ) {
                        console.log(result);
                        $('body').removeClass('loading');
                        if(result == "1"){
                           /* swal(
                                '<?= $trans['Deleted!'] ?>',
                                '<?= $trans['L\'organisme a été supprimé des informations de ce patient avec succès.'] ?>',
                                'success'
                            );*/
                            $(currentDiv).css('background-color', 'red').fadeOut(300, function(){ $(this).remove();});
                            //$('body').addClass('loading');
                            //window.location.reload();
                        }
                        else{
                            swal(
                                '<?= $trans['Annulé!'] ?>',
                                "<?= $trans['Annulé'] ?>",
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

        $('body').on('click', '.clotureBtn', function () {
            var demandeid = $(this).data('demandeid');
            var currentTr = $(this).closest("tr");
            swal(
                {
                    title: '<?= $trans['Are you sure?'] ?>',
                    text: "<?= $trans['Cette action validera cette demande'] ?>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= $trans['Oui, Clotûrer'] ?>',
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
                            url: "<?= $this->url->get('img_demandes/clotureDemande') ?>/" + demandeid,
                            cache: false,
                            async: true
                        })
                        .done(function( result ) {
                            $('body').removeClass('loading');
                            if(result == "1"){
                                swal(
                                    '<?= $trans['Validée!'] ?>',
                                    '<?= $trans['La demande a été clotûrée avec succès.'] ?>',
                                    'success'
                                );
                                $(currentTr).css('background-color', '#ff9933');
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
          data: <?= $dossiers_list ?>,
          cache: false,
          striped: true,
          pagination: true,
          pageSize: 10,
          pageList: [10, 25, 50, 100, 200],
          sortOrder: "desc",
          sortName: "date_creation",
          locale: "<?php if ($language == 'fr') { ?>fr-FR<?php } else { ?>en<?php } ?>",
          search: false,
          minimumCountColumns: 2,
          clickToSelect: false,
          toolbar: "#toolbar",
          showRefresh: false,
          showFooter: false,
          showLoading: false,
          showExport: false,
          showMultiSort: false,
          showPaginationSwitch: false,
          exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
          exportDataType : "selected",
          mobileResponsive: true,
          filterControl: false,
          columns: [
              {
                  field: 'date',
                  title: "<?= $trans['Date'] ?>",
                  sortable: true,
              },
              {
                  field: 'provenance',
                  title: "<?= $trans['Provenance'] ?>",
              },
              {
                  field: 'acte',
                  title: "<?= $trans['Prestations'] ?>",
              },
              {
                  field: 'etat',
                  title: "<?= $trans['Etat'] ?>",
              },
              <?php if (($userId == 1) || in_array('img_w', $userPermissions) || in_array('img_a', $userPermissions)) { ?>
              {
                  title: "Actions",
                  align: "left",
                  formatter: actionsFormatter,
              }
              <?php } ?>
          ]
      });

        submitAjaxForm();
     


    });
</script>