<!-- Content Wrapper. Contains page content -->
{{ content() }}

<div class="content-wrapper">
   {% if isMobile != true %}
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans['Support'] }}
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</a></li>
            <li>{{ trans['Support'] }}</li>
        </ol>
    </section>
    {% endif %}

    <div class="content">
        <?php $this->flashSession->output() ?>
        <div class="row">
            
            <div class="col-md-5">
                <div class="box box-solid">

                    <div class="box-body">
                        <img id="faq-img" src="{{ url('http://ipso.biz/wp-content/uploads/2012/12/FAQ.jpg') }}" alt="FAQ">
                    </div>

                </div>
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans['contact the support'] }}</h3>
                    </div> <!-- /.box-header -->

                    <div class="box-body">
                        {{ trans['send your question'] }}<br><br>
                        <form action="{{url('support/sendQuestion')}}" method="post" class="form-group">
                            <label>Message : </label>
                            <textarea class="form-control" name="sendbox" id="sendbox"></textarea>
                            <br>
                            <button type="submit" class="btn btn-primary pull-right ajax-navigation">{{ trans['Send'] }}</button>
                        </form>
                    </div> <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-7 col-lg-7">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans['faq'] }}</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="box-group" id="accordion">
                           {% for qr in faq %}
                            <div class="panel box box-solid">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <span class="num-question">{{ qr.weight }}</span>
                                        <a href="#collapse{{ qr.id }}" data-toggle="collapse" data-parent="#accordion">
                                            {{ qr.question }}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{ qr.id }}" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="box-body">
                                        {{ qr.response }}
                                    </div>
                                </div>
                            </div>
                            {% elsefor %}
                            <h4>{{ trans['no question'] }}</h4>
                            {% endfor %}
                        </div>
                    </div><!-- /.box-body -->

                </div><!-- /.box -->
            </div>
        </div>

    </div>
</div>
