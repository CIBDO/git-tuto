

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    {% if isMobile != true %}
    <section class="content-header">
        <h1>
            <i class="fa fa-fw fa-shopping-cart"></i> Commandes
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>{{ trans['Dashboard']}}</a></li>
            <li class="active">Commandes</li>
        </ol>
    </section>
    {% endif %}
    <!-- Main content -->

    <section class="content">

    informations
        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" style="background-image: url('{{ url("img/graph.png") }}'); background-size: cover; background-size: 115px 115px; background-repeat: no-repeat; background-position: right top">
                            <div class="row">
                                <div class="col-md-9">
                                    <h3>Chiffre d'affaires atteint</h3>
                                    <p> Chiffre d'affaires sur la semaine de 12 000 Eur dépassé :<br> <b>13 546,78 Eur</b></p>
                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                            <button type="button" class="btn btn-danger btn-block btn-lg">Accéder aux transactions</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>


        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" style="">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>Variation du panier moyen</h3>
                                    <p> Votre panier moyen journalier est de <b>552,85 Eur</b> et est en hausse de <b>452.86%</b> par rapport à votre objectif de 100,0 Eur.</p>
                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                            <button type="button" class="btn btn-success btn-block btn-lg">Accéder aux transactions</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" style="background-image: url('http://pointsgroupllccom.c.presscdn.com/wp-content/uploads/2014/05/an_icon.png'); background-size: cover; background-size: 115px 115px; background-repeat: no-repeat; background-position: right top">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>Transactions de crédit</h3>
                                    <blockquote>
                                        <p>Sur l'ensemble de vos terminaux, pour la journée du <b>2016-01-22</> :</p>
                                        <small>5 transactions de crédits CB ont été effectuées.</small>
                                        <small>3 transactions de crédits AMEX ont été effectuées.</small>
                                    </blockquote>
                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                            <button type="button" class="btn btn-success btn-block btn-lg">Accéder aux transactions</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        COMMUNICATION
        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="
                             padding-bottom: 0px;
                             padding-top: 0px;
                             padding-left: 0px;
                             padding-right: 0px;
                             ">
                            <div class="row">
                                <div class="col-md-12"><div class="pull-left"><img src="http://www.dri.pt/sites/default/files/images/blog/565.png" height="80" ></div><h1> &nbsp; Intervention sur site</h1></div>
                            </div>

                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-11">

                                    <blockquote>
                                        <p>Notre technicien interviendra le 12 février</p>
                                        <small>Livraison de rouleaux de papier</small>
                                        <small>Installation d'un nouveau terminal de paiement</small>
                                    </blockquote>

                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>




    PROMOTION

        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" style="background-image: url('http://www.golf-events.ch/golfevents/travel/images/offre-exclusive.png'); background-size: cover; background-size: 115px 115px; background-repeat: no-repeat; background-position: right top">
                            <div class="row">
                                <div class="col-md-11">
                                    <h3>Tourver toutes vos bobines papier</h3>
                                    <p>
                                        Trouvez toutes vos bobines papier chez Welcome Office au meilleur prix.<br>
                                        <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Bobine papier pour imprimante traceur,</label><br>
                                        <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> bobine pour calculatrice,</label><br>
                                        <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> bobine pour fax,</label><br>
                                        <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> bobine pour terminal carte bancaire</label><br>
                                        <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> et bobine pour caisse enregistreuse.</label>
                                    </p>
                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                            <button type="button" class="btn btn-default btn-block btn-lg">En savoir plus sur http://www.welcomeoffice.com</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        TICKET FIN DE JOURNEE
        <div class="example-modal">
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="
                             padding-bottom: 2px;
                             padding-top: 2px;
                             ">
                            <div class="row">
                                <div class="col-md-7"><h3>Ticket de fin de journée du <b>28/01/2016</b></h3></div>
                                <div class="col-md-5"><div class="pull-right"><img src="{{ url("img/retail_icon.png") }}" height="80" ></div></div>
                            </div>

                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-11">

                                    <blockquote>
                                        <p>SYNTHESE GLOBALE</p>
                                        <small>Nb total de débits : <cite title="Source Title">8</cite></small>
                                        <small>Somme totale des débits : <cite title="Source Title">2431,64 Eur</cite></small>
                                        <small>Nb total de crédits : <cite title="Source Title">8</cite></small>
                                        <small>Somme totale des crédits : <cite title="Source Title">0,55 Eur</cite></small>
                                    </blockquote>
                                    <blockquote>
                                        <p>VENTILATION PAR MOYEN DE PAIEMENT</p>
                                        <small>Télécollectes AMEX effectuées : <cite title="Source Title">1 / 1</cite></small>
                                        <small>Nb total de débits AMEX : <cite title="Source Title">5</cite></small>
                                        <small>Somme totale des débits AMEX : <cite title="Source Title">6,66 Eur</cite></small>
                                        <small>Nb total de crédits AMEX : <cite title="Source Title">5</cite></small>
                                        <small>Somme totale des crédits AMEX : <cite title="Source Title">0,5R Eur</cite></small>
                                        <small>Télécollectes CB effectuées : <cite title="Source Title">3 / 1</cite></small>
                                        <small>Nb total de débits CB : <cite title="Source Title">3</cite></small>
                                        <small>Somme totale des débits CB : <cite title="Source Title">2 424,98 Eur</cite></small>
                                        <small>Nb total de crédits CB : <cite title="Source Title">3</cite></small>
                                        <small>Somme totale des crédits CB : <cite title="Source Title">0,5R Eur</cite></small>
                                    </blockquote>
                                </div>
                                <!--<div class="col-md-3">
                                    <img src="{{ url("img/tag-euro.png") }}" width="100" >
                                </div>-->
                            </div>
                            <button type="button" class="btn btn-info btn-block btn-lg">Acceder aux transactions</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
