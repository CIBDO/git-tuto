
{{ content() }}
<div class="content-wrapper mobileWrapper" style="min-height: 100%">
<?php $this->flashSession->output() ?>
        <!-- Header -->
        <div class="box box-default color-palette-box">
            <div class="col-xs-12" style="background-color: white;padding: 0px;padding-top:5px">
                <div class="box-body" style="
    padding: 0px;
">
                    <div class="col-xs-12">
                        <div class="row">
                        {% if services is defined and services.code is not defined %}
                            {% for service in services %}
                                {% if service.onActivity == 1 %}
                                <div class="col-xs-3">
                                    <center>
                                        <a href="{{url('payments/')}}?service={{service.id}}" style="color: black;" class="ajax-navigation">
                                            <img src="{{service.logo}}" class="img-responsive" alt="logo">
                                            <h5 class="text-center">{{service.label}}</h5>
                                        </a>
                                    </center>
                                </div>
                                {% endif %}
                            {% elsefor %}
                                <div class="col-xs-12">
                                    <center>
                                        Aucun services actif sur votre compte
                                    </center>
                                </div>
                            {% endfor %}
                        {% else %}

                                <center style="padding-top:10px;padding-bottom: 10px">
                                    Aucun services actif sur votre compte
                                </center>

                        {% endif %}
                    </div>
                </div>
        <!-- End header -->
        {% if pubs is defined %}
        <div class="row">
        <div class="col-xs-12">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="3000">
                <div class="carousel-inner">
                {% for key, pub in pubs %}

                    <div class="item {% if key == 0 %}active{% endif %} slidePub" id="{{pub.id}}" href="{{pub.url}}">
                        <img src="{{url(pub.img)}}" alt="slide" class="img-responsive" style="width:100%;">
                    </div>

                {% endfor %}
                </div>
            </div>
            </div>
        </div>
        {% endif %}
         <!-- {% if pubs is defined %}
        <div class="row">
        <div class="col-xs-12">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                {% for pub in pubs %}
                    <div class="item">
                        <img src="http://fronttest.opsise.local/bo/img/pub1.png" alt="First slide" class="img-responsive" style="width:100%;min-height: 200px;">
                    </div>
                {% endfor %}
                </div>
            </div>
            </div>
        </div>
        {% endif %} -->

        <div class="row ">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6" style="margin-top: 10px !important;padding:0 !important;">
                        <a href="{{url('referral/index')}}" class="ajax-navigation">
                        <div class="small-box bg-blue-gradient" style="border: 1px solid gray;border-radius: 0px !important;width:96%;margin-bottom: 10px;">

                            <div class="inner">
                                <h5>
                                    <center>
                                        <b><i class="fa fa-thumbs-o-up margin-bottom-5"></i><br />
                                        &nbsp;&nbsp;Parrainages&nbsp;&nbsp;</b>
                                    </center>
                                </h5>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-xs-6" style="margin-top: 10px !important;padding:0 !important;">
                        <a href="{{url('pointsofsale/index')}}" class="ajax-navigation">
                        <div class="small-box bg-aqua-active" style="border: 1px solid gray;border-radius: 0px !important;width:96%;margin-bottom: 10px;">

                            <div class="inner">
                                <h5>
                                    <center>
                                        <b><i class="fa fa-home margin-bottom-5"></i><br />
                                        &nbsp;&nbsp;Mon Commerce&nbsp;&nbsp;</b>
                                    </center>
                                </h5>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6" style="margin-top: 0px !important;padding:0 !important;">
                        <a href="{{url('notifications/index')}}" class="ajax-navigation">
                        <div class="small-box bg-orange-active" style="border: 1px solid gray;border-radius: 0px !important;width:96%;margin-bottom: 10px;">
                            <div class="inner">
                                <h5>
                                    <center>
                                        <b><i class="fa fa-bell margin-bottom-5"></i><br />
                                        &nbsp;&nbsp;Mes Notifications&nbsp;&nbsp;</b>
                                    </center>
                                </h5>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-xs-6" style="margin-top: 0px !important;padding:0 !important;">
                        <a href="{{url('resume/index')}}" class="ajax-navigation">
                        <div class="small-box bg-aqua" style="border: 1px solid gray;border-radius: 0px !important;width:96%;margin-bottom: 10px;">
                            <div class="inner">
                                <h5>

                                    <center>
                                        <b><i class="fa fa-euro margin-bottom-5"></i><br />
                                        &nbsp;&nbsp;Synthèse&nbsp;&nbsp;</b>
                                    </center>

                                </h5>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Box Comment -->
                <div class="box box-widget">
                    <div class="box-header with-border" style="
    padding-top: 5px;
    padding-bottom: 5px;
">
                        <div class="user-block">
                            <img class="img-circle" src="{{ reseller.logo }}" alt="Reseller Logo">
                            <span class="username" style="color: #3c8dbc;">{{ reseller.name }}</span>
                            <span class="description">{{ trans['contact us'] }} {{ reseller.adminPhone }}</span>
                        </div>
                        <!-- /.user-block -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="
    padding-top: 5px;
    padding-bottom: 0px;
">
                        <div class="col-xs-12" style="margin-top: 10px;margin-bottom:0px">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="{{url('contact')}}" class="ajax-navigation">
                                    <center>
                                        <img src="{{url('img/support.jpg')}}" height="35" width="35" class="img-responsive" alt="contact">
                                        <h5><span class="description-text">Assistance</span></h5>
                                    </center>
                                </a>
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->
                            <div class="col-xs-6">
                                <a href="{{url('notifications/exchanges')}}" class="ajax-navigation">
                                    <center>
                                        <img src="{{url('img/exchange.png')}}" height="35" width="35" class="img-responsive" alt="exchanges">
                                        <h5><span class="description-text">Echanges</span></h5>
                                    </center>
                                </a>
                            </div>

                        </div>
                    </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>

</div>
</div>

<script type="text/javascript">
var times = '{{times}}';
var defaultTime = 3000;
console.log(times);
$('.carousel').on('slide.bs.carousel', function () {
    var activeItem = $('.item.active');
    var nextTime = getTimeByID(activeItem.attr('id'));
    if(nextTime == "undefined"){
        nextTime = defaultTime;
    }
    $(this).data("bs.carousel").options.interval = nextTime;
 });

function getTimeByID(id){
    var timesSlide = JSON.parse(times);
    for (var i = timesSlide.length - 1; i >= 0; i--) {
        if(timesSlide[i]['id'] == id){
            return timesSlide[i]['value'];
        }
    };

}
</script>
