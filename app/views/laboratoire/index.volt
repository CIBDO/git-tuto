
{{ content() }}
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

          <h1>Résultat d'analyse</h1>

          <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Laboratoire d'analyse médicale</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <?php $this->flashSession->output() ?>

          <div class="row">
            <div class="col-md-6">

              <div class="box box-info box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Informations Patient</h3>
                </div>

                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <tbody>

                      <tr>
                        <th>Nom</th>
                        <td>Toto</td>
                      </tr>
                      <tr>
                        <th>Prenom</th>
                        <td>Dupond</td>
                      </tr>
                      <tr>
                        <th>Age</th>
                        <td>28 ans</td>
                      </tr>
                      <tr>
                        <th>Date de derniere visite</th>
                        <td>02/02/2016</td>
                      </tr>
                      <tr>
                        <th>Autres infos</th>
                        <td>-----</td>
                      </tr>

                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
              </div>

            </div><!-- /.col -->

            <div class="col-md-6">

              <div class="box box-info box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Informations sur le dossiers</h3>
                </div>

                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <tbody>

                      <tr>
                        <th>Date de la demande</th>
                        <td>07/04/2016</td>
                      </tr>
                      <tr>
                        <th>Numéro paillasse</th>
                        <td>1-07052015</td>
                      </tr>
                      <tr>
                        <th>Provenance</th>
                        <td>Externe</td>
                      </tr>
                      <tr>
                        <th>Prescripteur</th>
                        <td>Dr Obilux</td>
                      </tr>
                      <tr>
                        <th>Infos clinique</th>
                        <td>ABCD EFG</td>
                      </tr>

                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
              </div>

            </div><!-- /.col -->

          </div><!-- /.row -->

          <div class="row">
            <div class="margin">
                <div class="btn-group">
                  <button type="button" class="btn btn-info">Action</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default">Action</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                
                <div class="btn-group">
                  <button type="button" class="btn btn-warning">Action</button>
                  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              </div>
          </div>

          <div class="row">
            <div class="col-md-12">

              <div class="box box-info box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Resultat</h3>
                </div>

                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                          <tr>
                            <th>Analyses</th>
                            <th>Resultats</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                          <tr>
                            <th>Element 1</th>
                            <td>resultat 1</td>
                            <td><i class="fa fa-remove"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i></td>
                          </tr>
                          <tr>
                            <th>Element 2</th>
                            <td>resultat 2</td>
                            <td><i class="fa fa-remove"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i></td>
                          </tr>
                          <tr>
                            <th>Element 3</th>
                            <td>resultat 3</td>
                            <td><i class="fa fa-remove"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i></td>
                          </tr>
                          <tr>
                            <th>Element 4</th>
                            <td>resultat 4</td>
                            <td><i class="fa fa-remove"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i></td>
                          </tr>
                          <tr>
                            <th>Element 5</th>
                            <td>resultat 5</td>
                            <td><i class="fa fa-remove"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i></td>
                          </tr>

                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>

                <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-right">Enregistrer</a>
                </div>

              </div>

            </div><!-- /.col -->

          </div><!-- /.row -->

        </section>
      </div>

