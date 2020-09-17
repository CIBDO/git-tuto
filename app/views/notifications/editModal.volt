{{ stylesheet_link('css/opsise.css') }}
<form action="{{url('notifications/edit')}}" method="post" role="form">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">

          <div class="activationCheck pull-right" style="display:block" >
  <label class="label" style="color: white; font-size: 13px;line-height: 30px;vertical-align:top">{{trans['active']}} :</label>&nbsp;
  <div class="onoffswitch" style="display:inline-block">
    <input type="checkbox" name="activation_check_edit" class="onoffswitch-checkbox" id="activation_check_edit" {% if usersAlerts.activated == 1 %} checked {% endif %}>
    <label class="onoffswitch-label" for="myonoffswitch">
        <span class="onoffswitch-inner"></span>
    </label>
  </div>
</div>

          <h4 class="modal-title">{{trans['Configurer une notification']}}</h4>
        </div>
        <div class="modal-body" id="">
          <div class="box box-primary">
            <div class="box-header with-border">
              
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body" style="color:black;">
              <div class="form-group">

                <select name="alert_type" id="alert_type" class="form-control" readonly>
                  <option value="{{ alert.id }}">{{alert.title}}</option>
                </select>
                <br>
                <div class="callout bg-gray-light disabled color-palette" style="border-color: #00c0ef">
                  <p>{{alert.description}}</p>
                  <div id="frequency">
                    {% if alert.frequency is defined %}
                        <span style="color: gray">{{trans['alert_frequency']}} : {{alert.frequency}}</span>
                    {% endif %}
                  </div>
                </div>
                {% if alert.type == "TRIGGER" %}
                  <div id="frequency">
                    <label>{{trans['Choisissez votre occurence']}} : </label>&nbsp;&nbsp;

                      {% for frequency in criteria.frequency %}
                        {% if sizeof(criteria.frequency) > 1 %}
                        
                        <label class="checkbox-inline dayRangemargin">
                          <input type="checkbox" id="frequency-{{frequency}}" name="frequency-{{frequency}}" value="{{frequency}}" class="update-frequencyCheckBox" {% if strstr(alreadyDayRange, frequency) %} checked {% endif %}>{{trans[frequency]}}
                        </label>
                        {% else %}
                          <input type="hidden" id="frequency-{{frequency}}" name="frequency-{{frequency}}" value="{{frequency}}" class="update-frequencyCheckBox" {% if strstr(alreadyDayRange, frequency) %} checked {% endif %}>

                          <input type="checkbox" checked disabled />&nbsp;&nbsp;{{trans[frequency]}}
                        {% endif %}
                      {% endfor %}
                    
                  </div>
                <br>
                <div class="nav-tabs-custom" id="updatetab" style="">
                    <ul class="nav nav-tabs">
                    {% set activated = 0 %}
                    {% for key, frequency in criteria.frequency %}
                      <li class="{% if activated == 0 AND in_array(frequency, alreadyDayRangeArray) %} active {% set activated = 1 %}{% endif %}"><a href="#update-tabcontent_{{frequency}}" data-toggle="tab" aria-expanded="true" id="update-tab-{{frequency}}" style="{% if !strstr(alreadyDayRange, frequency) %} display: none {% endif %}">{{trans[frequency]}}</a></li>
                    {% endfor %}
                    </ul>
                    <div class="tab-content">
                      {% for key, frequency in criteria.frequency %}
                      <div class="tab-pane {% if key == 0 %}active{% endif %}" id="update-tabcontent_{{frequency}}">

                      {% for champ in criteria.fields %}
                        <div class="form-group">
                          <div class='row'>
                            <div class="col-md-12">
                            <label class="label col-sm-2 vcenter" style="color:gray;vertical-align:middle;display:table"> {{trans[champ]}} : </label>
                              <div class='input-group'>
                                <input type="text" name="{{frequency}}-{{champ}}" value="{% if usercriteria['triggervalues'][frequency] is defined and usercriteria['triggervalues'][frequency][champ] is defined %}{% if fieldsType[champ] == "currency" %}{{usercriteria['triggervalues'][frequency][champ] / 100}}{% else %}{{usercriteria['triggervalues'][frequency][champ]}}{% endif %}{% endif %}" class="form-control form-control-no-width col-sm-4"/>
                                <span class="input-group-addon">
                                  {% if fieldsType[champ] == "currency" %}
                                  ,00€
                                  {% elseif fieldsType[champ] == "percent" %}
                                    %
                                  {% elseif fieldsType[champ] == "credits" %}
                                    credits
                                  {% else %}
                                    {{fieldsType[champ]}}
                                  {% endif %}
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      {% endfor %}
                      {% if frequency == "daily" %}
                        {% for day in dayRange %}
                          <label class="checkbox-inline dayRangemargin">
                            <input type="checkbox" id="dateRange{{day}}" name="dateRange{{day}}" value="{{day}}" {% if strstr(useralertsDayRange, day) %} checked {% endif %}><!-- {% if strstr(dayRange, "{{frequency}}") %}checked{% endif %} -->
                            {{trans[day]}}
                          </label>
                        {% endfor %}
                      {% endif %}
                      </div>
                      {% endfor %}
                      <!-- /.tab-pane -->
                    <!-- /.tab-content -->
                  </div>

                {% else %}
                <div id="frequency">
                      <label>Choisissez votre occurence : </label>&nbsp;&nbsp;
                      <input type="checkbox" checked disabled />&nbsp;&nbsp;Daily
                </div>
                <br>
                 <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">

                    <li class="active"><a href="#tab_1" data-toggle="tab">{{trans['daily']}}</a></li>

                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                      {% for key, champ in criteria.fields %}
                    <div class="form-group">
                      <div class='row'>
                        <div class="col-md-12">
                        <label class="label col-sm-2 vcenter" style="color:gray;vertical-align:middle;display:table"> {{trans[champ]}} : </label>
                        {% for critere in usercriteria["livevalues"]["daily"] %}
                          {% if critere['type'] == "raw_value" %}
                          
                          <select class="form-control form-control-no-width col-sm-6" style="margin-right: 10px;margin-bottom:10px;" name="operator-{{champ}}" id="operator-{{champ}}">
                              <option value=">=" {% if usercriteria["livevalues"]["daily"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] == ">=" %} selected {% endif %}> {{trans["higher_or_equal_to"]}} </option>
                              <option value="<=" {% if usercriteria["livevalues"]["daily"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] == "<=" %} selected {% endif %}>{{trans["lower_or_equal_to"]}} </option>
                              <option value="==" {% if usercriteria["livevalues"]["daily"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] is defined and usercriteria["livevalues"]["daily"][key+2]["value"] == "==" %} selected {% endif %}>{{trans["equal_to"]}} </option>
                            </select>
                            <div class='input-group'>
                              <input type="text" name="{{champ}}" value="{% if fieldsType[champ] == "currency" %}{{critere["value"] / 100 }}{% else %}{{critere["value"]}}{% endif %}" class="form-control form-control-no-width col-sm-4"/>
                              <span class="input-group-addon">
                              {% if fieldsType[champ] == "currency" %}
                                    ,00€
                                  {% elseif fieldsType[champ] == "percent" %}
                                    %
                                  {% elseif fieldsType[champ] == "credits" %}
                                    credits
                                  {% else %}
                                    {{fieldsType[champ]}}
                              {% endif %}
                              </span>
                            </div>
                          {% endif %}
                        {% endfor %}
                        </div>
                      </div>
                    </div>
                  {% endfor %}
                  {% for day in dayRange %}
                    <label class="checkbox-inline dayRangemargin">
                      <input type="checkbox" id="dateRange{{day}}" name="dateRange{{day}}" value="{{day}}" {% if strstr(useralertsDayRange, day) %} checked {% endif %}><!-- {% if strstr(dayRange, "{{frequency}}") %}checked{% endif %} -->
                      {{trans[day]}}
                    </label>
                  {% endfor %}
                  </div>
                    <!-- /.tab-pane -->
                  </div>
                  <!-- /.tab-content -->
                </div>
                {% endif %} 
                    
                  
                
                  
                </div>
                
                
                <!-- <div class="activationCheck" style="">
                    <label class="label" style="color:red;font-size:13px">Activer dès maintenant :</label>
                    <input type="checkbox" class="checkbox-inline" name="activation_check_edit" id="activation_check_edit" {% if usersAlerts.activated == 1 %} checked {% endif %}/>
                </div> -->
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">{{trans['Close']}}</button>
          <button type="submit" class="btn btn-outline ajax-navigation" title="Vous devez remplir tous les champs obligatoire">{{trans['Save']}}</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </form>

  <script type="text/javascript">
    $('body').on('click', '.update-frequencyCheckBox', function(){
    var target = $(this).attr('id').split('-')[1];
    var countCheck = $(".update-frequencyCheckBox:checked").length;
    if($(this).is(':checked')){
        $('.nav-tabs-custom#updatetab').css('display', 'block');
        $('#update-tab-' + target).css('display', 'block');
        if(countCheck == 1){
          console.log($('#tabcontent_' + target).parent());
          $('#update-tabcontent_' + target).addClass('active');
          $('#update-tab-' + target).parent().addClass('active');
        }
    } else {

        $('#update-tab-' + target).css('display', 'none');
        $('#update-tab-' + target).parent().removeClass('active');
        $('#update-tabcontent_' + target).removeClass('active');
        if(countCheck == 0){
          $('.nav-tabs-custom#updatetab').css('display', 'none');

        }
    }
});


  $('.onoffswitch-label').on('click', function(){
    var checked = $('#activation_check_edit').prop('checked');
    console.log(checked);
    if(!checked || checked == "undefined" || checked == ""){
      $('#activation_check_edit')[0].checked = true;
      $('#activation_check_edit').attr('checked', '');
    }
    else
    {
      $('#activation_check_edit').removeAttr('checked');
    }
  });

    </script>
