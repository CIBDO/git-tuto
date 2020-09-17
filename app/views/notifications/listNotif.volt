

<div id="notif_containers">

{% if isMobile == true %}
    <!-- Here is the second panel ( Voir ) -->
      <div class="col-md-12">
        <h3>
          <small>{{trans['My notifications received since']}} {{startDate}}</small><br><br>
          <button class="btn  btn-primary pull-right" id="allRead" style="margin-right: 25px;" title="{{trans['Mark as read']}}">
            <i title="{{trans['unread']}}" style="font-size:17px;" class="fa fa-fw fa-eye"></i>
            {{trans['Mark as read']}}
          </button>
        </h3>

      </div>
      <div class="col-md-12">

      <!-- The time line -->
      <ul class="timeline">

        {% for index, notifsDate in notificationsFull %}

        <li class="time-label">
          <span class="custom-bg">
            {{ index }}
          </span>
        </li>
        {% for notifs in notifsDate %}
        <li>
          {% if notifs.type == "INFO" %}
          <i class="fa fa-info bg-blue"></i>
          {% else %}
          <i class="fa fa-bell-o bg-yellow"></i>
          {% endif %}
          <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i>
              {{ date('h:i', notifs.created) }}
              {% if notifs.read == 0 %}
              <i title="{{trans['unread']}}" style="font-size:17px;color: green" class="fa fa-fw fa-eye-slash"></i>
              {% else %}
              <i title="{{trans['read']}}" style="font-size:17px;color:green" class="fa fa-fw fa-eye"></i>
              {% endif %}
            </span>

            <h3 class="timeline-header"><a href="#" >{{notifs.title}}</a></h3>

            <div class="timeline-body">
              {% if notifs.read == 0 %}
              <span style="font-weight: bold;">{{ notifs.message }}</span>
              {% else %}
              <span>{{ notifs.message }}</span>
              {% endif %}
            </div>
            {% if notifs.url is not null %}
            <div class="timeline-footer">
              <a class="btn btn-primary btn-xs" target="_blank" href="{{notifs.url}}" title="{{trans['More informations']}}">{{trans['More informations']}}</a>
            </div>
            {% endif %}

          </div>
        </li>
        {% endfor %}
        {% endfor %}
        <li>
          <i class="fa fa-clock-o bg-gray"></i>
        </li>
      </ul>
    </div>
{% else %}

<!-- Content Header (Page header) -->
<section class="content-header" >
  <div class="col-md-7">
    <h3>
      <small>{{trans['My notifications received since']}} {{startDate}}</small>
      <button class="btn  btn-primary pull-right" id="allRead" style="margin-right: 25px;" title="{{trans['Mark as read']}}">
        <i title="{{trans['unread']}}" style="font-size:17px;" class="fa fa-fw fa-eye"></i>
        {{trans['Mark as read']}}
      </button>
    </h3>

  </div>
<!--
  <ol class="breadcrumb">
    <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</a></li>
    <li><i class="fa fa-gear"></i> {{ trans['Settings'] }}</li>
    <li class="active"> Notifications</li>
  </ol>
-->
</section>
<!-- Main content -->
<section>
  <div class="row"><br></div>

    <div class="col-md-7">

      <!-- The time line -->
      <ul class="timeline">

        {% for index, notifsDate in notificationsFull %}

        <li class="time-label">
          <span class="custom-bg">
            {{ index }}
          </span>
        </li>
        {% for notifs in notifsDate %}
        <li>
          {% if notifs.type == "INFO" %}
          <i class="fa fa-info bg-blue"></i>
          {% else %}
          <i class="fa fa-bell-o bg-yellow"></i>
          {% endif %}
          <div class="timeline-item" id="{{notifs.id}}">
            <span class="time"><i class="fa fa-clock-o"></i>
              {{ date('h:i', notifs.created) }}
              {% if notifs.read == 0 %}
              <i title="{{trans['unread']}}" style="font-size:17px;color: green" class="fa fa-fw fa-eye-slash"></i>
              {% else %}
              <i title="{{trans['read']}}" style="font-size:17px;color:green" class="fa fa-fw fa-eye"></i>
              {% endif %}
            </span>

            <h3 class="timeline-header"><a href="#" >{{notifs.title}}</a></h3>

            <div class="timeline-body">
              {% if notifs.read == 0 %}
              <span style="font-weight: bold;">{{ notifs.message }}</span>
              {% else %}
              <span>{{ notifs.message }}</span>
              {% endif %}
            </div>
            {% if notifs.url is not null %}
            <div class="timeline-footer">
              <a class="btn btn-primary btn-xs" target="_blank" href="{{notifs.url}}" title="{{trans['More informations']}}">{{trans['More informations']}}</a>
            </div>
            {% endif %}

          </div>
        </li>
        {% endfor %}
        {% endfor %}
        <li>
          <i class="fa fa-clock-o bg-gray"></i>
        </li>
      </ul>
    </div>
</section>




      {% endif %}
</div>

<script type="text/javascript">
    function updateCounts(){
        $( ".notification_counter" ).each(function( index ) {
            $(this).text('0');
        });
    }

    function addColoration() {
        var lastColorIndex;
        $( ".custom-bg" ).each(function( index ) {
            var custombg = new Array('bg-green', 'bg-yellow', "bg-blue", 'bg-purple');
            var randomnumber=Math.floor(Math.random()*custombg.length);
            while(randomnumber == lastColorIndex){
                randomnumber=Math.floor(Math.random()*custombg.length);
            }
            $(this).addClass(custombg[randomnumber]);
            lastColorIndex = randomnumber;
        });
    }

    addColoration();
</script>

