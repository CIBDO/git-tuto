
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
  <i class="fa fa-bell-o"></i>
  <span class="label label-warning notification_counter">{% if notificationsCount is defined %}{{ notificationsCount }}{% else %} 0 {% endif %}</span>
</a>
<ul class="dropdown-menu nomax" style="border-top: 5px solid #f97726" id="dropdown-menu-notifications">

  <li class="header text-center" style="color:gray"><span>{{trans['Your notifications']}}</span></li>
  <li>
    <!-- inner menu: contains the actual data -->
    <div class="slimScrollDiv" style="position: relative; overflow:hidden;width: auto; height: auto;"><ul class="menu nomax" style="width: 100%;overflow:hidden; height: auto;">
      
            {% if notifications is defined %}
      {% for notification in notifications %}
      <li class="readOnce" id="{{notification.id}}">
      <a href="#">
      <div class="row">
          <div class="col-md-2">
          <br />
            {% if notification.type == "INFO" %}
                <span class="label label-info">{{ notification.type }}</span> 
            {% else %}
                <span class="label label-danger">{{ notification.type }}</span>
            {% endif %}
          </div>
          <div class="col-md-10">
                <div class="col-md-12">
                    <span style="color:gray;font-size:10px;font-style: italic;">{{ notification.date }}</span>
                </div>
                <br />
                <div class="col-md-12" >
                    <span style="color:gray;font-size:10px;font-weight:bold" class="word_wrap">{% if strlen(notification.message) > 150 %} {{ substr(notification.message, 0, 150) }} <span style="color: #71a4cb">{{trans['Read more']}} ... </span>{% else %} {{notification.message}} {% endif %}</span>
                </div>
            </div>
        </div>
        </a>
      </li>
      {% elsefor %}
        <div class="col-md-12">
            <span class="notif_text text-center"><br>{{trans['no_notifications']}}<br></span>
        </div>
      {% endfor %}
      {% else %}
        <div class="col-md-12">
            <span class="notif_text text-center"><br>{{trans['no_notifications']}}<br></span>
        </div>
      {% endif %}
    </ul><div class="slimScrollBar" style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
  </li>
  <li class="footer">
    <div class="col-md-6" style="margin: 0px;padding: 0px;">
        <button type="button" id="allRead" class="btn btn-block btn-default btn-flat" style="color:gray;" title="{{trans['Mark Read']}}">{{trans['Mark Read']}}</button>
    </div>
    <div class="col-md-6" style="margin: 0px;padding: 0px;">
        <a href="{{url('notifications/index')}}" class="btn btn-block btn-default btn-flat ajax-navigation" style="color:gray;" title="{{trans['View all']}}"> {{trans['View all']}}</a>
    </div>
    </li>
</ul>