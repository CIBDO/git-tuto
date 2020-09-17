
{% if isMobile == true %}
  <div class="col-md-12" style="">
{% else %}
  <div class="col-md-5" style="">
{% endif %}
      <div class="box box-primary" style="margin-top: 10px">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-bell"></i> {{trans['Declared notifications']}}</h3>
        </div>
        <div class="box-body">

          <table class="table table-striped">
            <tr>
              <th>{{trans['Title']}}</th>
              {% if isMobile != true %}
                <th>{{trans['Creation Date']}}</th>
                <th>{{trans['Last send']}}</th>
              {% endif %}
              <th style="width: 40px"></th>
              <th style="width: 40px"></th>
            </tr>

            {% if usersAlerts.code is not defined %}
            {% for usersAlert in usersAlerts %}
            {% for catalog in alertsCatalog %}
            {% if usersAlert.alerts_id == catalog.id %}
            {% if usersAlert.activated == 0 %}
            <tr style="color: gray;">
              {% else %}
              <tr>
                {% endif %}
                <td>{{ catalog.title }}</td>
                {% if isMobile != true %}
                    <td>{{usersAlert.created}}</td>
                    <td>{{usersAlert.lastPush}}</td>
                {% endif %}
                <td class="text-center editModalToggle cursor-pointer" style="font-size: 18px" data-toggle="modal" data-target="#editModal" id="{{catalog.id}}"><i class="fa fa-gears"></i></td>

                <td class="text-center" style="font-size: 18px">
                    <a style="color:#444;" href="#" class="cursor-pointer deletePopup" data-id="{{ catalog.id }}"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              {% endif %}
              {% endfor %}
              {% endfor  %}
              {% else %}
              <tr>
                <td></td>
              </tr>
              {% endif %}

            </table>
            <br>
            <button type="submit" class="btn btn-primary pull-left cursor-pointer" title="{{trans['Add']}}" data-toggle="modal" data-target="#newModal">{{trans['Add']}}</button>
          </div>
        </div>

        <div class="box box-default color-palette-box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tablet"></i> {{trans['Devices']}}</h3>
          </div>
          <div class="box-body">
            <table class="table table-striped">
              <tr>
                <th style="width: 80px"></th>
                <th>{{trans['brand']}}</th>
                <th>{{trans['model']}}</th>
                <th>{{trans['Activation date']}}</th>
                <th style="width: 40px"></th>
              </tr>

              {% if devices is defined and devices.code is not defined %}
              {% for device in devices %}
              <tr>
                <td><img src='{{url(device.img)}}' height="30px;" alt="device"></td>
                <td style="vertical-align: middle">{{device.brand}}</td>
                <td style="vertical-align: middle">{{device.model}}</td>
                <td style="vertical-align: middle">{{ date('d-m-y h:i:s', device.created) }}</td>
                <td class="text-center" style="font-size: 18px">
                    <a href="#" style="color:#444;" class="deletePopupPhone cursor-pointer" data-id="{{ device.id }}"> <i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              {% elsefor %}
              <tr>
                  <td colspan="5">
                  <center>Aucun téléphone enregistré</center>
                  </td>
              </tr>
              {% endfor %}
              {% else %}
                <tr>

                 <td colspan="5">
                  <center>Aucun téléphone enregistré</center>
                  </td>
                </tr>
              {% endif %}
            </table>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
        </div>

        <div class="box box-warning color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cube"></i> {{ trans['suggestion box'] }}</h3>
        </div>
        <div class="box-body">
            {{ trans['propose your idea'] }}<br><br>
          <form action="{{url('notifications/sendbox')}}" method="post" class="form-group">
            <label>Message : </label>
            <textarea class="form-control" name="sendbox" id="sendbox"></textarea>
            <br>
            <button type="submit" class="btn btn-primary pull-right ajax-navigation">{{ trans['Send'] }}</button>
          </form>
        </div>
       </div>

      </div>

