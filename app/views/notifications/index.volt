
<div class="content-wrapper">

    <?php $this->flashSession->output() ?>
    {% if isMobile == true %}
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active" style="width: 50%"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">{{trans['Configuration_alert']}}</a></li>
        <li role="presentation" style="width: 50%"><a href="#voir" aria-controls="voir" role="tab" data-toggle="tab">{{trans['View_alert']}}</a></li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="config">
          <!-- Here is the first panel ( config ) -->
          {{ partial('notifications/notifConfiguration')}}
        </div>
        <div role="tabpanel" class="tab-pane" id="voir">
        {# {{ partial('notifications/listNotif#messages')}} #}
        </div>
      </div>
    {% else %}
    <div class="content-header">
        <h1>{{ trans['notifs history'] }}</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url("index") }}"><i class="fa fa-dashboard"></i> {{ trans['Dashboard']}}</a></li>
            <li><a href="#"><i class="fa fa-gear"></i> {{ trans['Settings'] }}</a></li>
            <li class="active"> Notifications</li>
        </ol>
    </div>

    <div class="content">
      <div class="row">
        {{ partial('notifications/listNotif')}}
        {{ partial('notifications/notifConfiguration')}}
      </div>
    </div>
    {% endif %}

</div>
<!-- Create Modal -->
<div class="modal modal-primary fade" id="newModal" tabindex="-1" role="dialog">
  <form action="{{url('notifications/create')}}" method="post" role="form">

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <div class="activationCheck pull-right" style="display:none" >
              <label class="label" style="color: white; font-size: 13px;line-height: 30px;vertical-align:top">{{trans['active']}} :</label>&nbsp;

              <div class="onoffswitch" style="display:inline-block">
                <input type="checkbox" name="activation_check" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                <label class="onoffswitch-label" for="myonoffswitch">
                  <span class="onoffswitch-inner"></span>
                </label>
              </div>
            </div>
            <h4 class="modal-title">{{trans['New alert configuration']}}</h4>

          </div>
          <div class="modal-body">
            <div class="box box-primary">
              <div class="box-header with-border">
                {{trans['alert_type_choose']}}

              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <div class="box-body" style="color:black;">
                <div class="form-group">
                  <select name="alert_type" id="alert_type" class="form-control" data-placeholder="Select a State">
                  <option id="noChoice" value="NO_CHOICE" disabled selected>---- {{trans['choose_alert']}} ---- </option>
                    {% for catalog in alertsCatalog %}
                      {% if !in_array(catalog.id, alreadyUsed) %}
                      <option value="{{ catalog.id }}">{{catalog.title}}</option>
                      {% endif %}
                    {% endfor %}
                  </select>
                  <br>


                  <div id="form_container">

                  </div>

                  <div id="frequency">

                  </div>
                  <br>
                  <div class="nav-tabs-custom" id="nav_global_tab" style="display:none">
                    <ul class="nav nav-tabs">
                      <li class=""><a href="#tabcontent_daily" data-toggle="tab" aria-expanded="true" id="tab-daily" style="display:none">{{trans['daily']}}</a></li>
                      <li class=""><a href="#tabcontent_weekly" data-toggle="tab" aria-expanded="false" id="tab-weekly" style="display:none">{{trans['weekly']}}</a></li>
                      <li class=""><a href="#tabcontent_monthly" data-toggle="tab" aria-expanded="false" id="tab-monthly" style="display:none">{{trans['monthly']}}</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane" id="tabcontent_daily">
                        Daily
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tabcontent_weekly">
                        Weekly
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tabcontent_monthly">
                        Monthly
                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div>





                </div>
              </div>
              <!-- /.box-body -->


            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left " data-dismiss="modal">{{trans['Close']}}</button>
            <button type="submit" class="btn btn-outline ajax-navigation" title="Vous devez remplir tous les champs obligatoire">{{trans['Save']}}</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
    </form>
    <!-- /.modal-dialog -->
  </div>

  <!-- Create Modal -->
  <div class="modal modal-primary fade" id="editModal" tabindex="-1" role="dialog">



    <!-- /.modal-dialog -->
  </div>



<script type="text/javascript">
    //Add by mbamba on 15/02/2016 : Show the notifs list after clicking on "Messages" button in the home page
    {% if isMobile == true %}
    var hash = document.location.hash;
    //If there is the hash
    if (hash === "#messages") {
        $('body').addClass('loading');
        // Active the right tab
        $('.nav-tabs a[href="#voir"]').parent("li").addClass("active");
        $('.nav-tabs a[href="#config"]').parent("li").removeClass("active");
        $("#voir").addClass('active');
        $("#config").removeClass('active');

        // Call the notifs list
        $.get("{{url('notifications/listNotif')}}", {page : "messages"}, function (data) {
            $("#voir").html(data);
            $('body').removeClass('loading');
        });
    } else {
        $('body').addClass('loading');
        $.get("{{url('notifications/listNotif')}}", function (data) {
            $("#voir").html(data);
            $('body').removeClass('loading');
        });
    }

    {% endif %}
    //Fin

    $(document).on("click", '#allRead', function(event) {
        $('body').addClass('loading');
      $.ajax({
        url: "{{url('notifications/setNotificationsReadAll/')}}",
      }).done(function(data) {

        $('#notif_containers_box').html(data);
        addColoration();
        $('body').removeClass('loading');
        updateCounts(); // If request is ok, reset counters
      });
    });

    var catalogJson = {{alertsCatalogJson}};
    function updateCounts(){
      $( ".notification_counter" ).each(function( index ) {
        $(this).text('0');
      });
    }


    $('#alert_type').change(function() {

      createFormByAlertID($(this).val());
    });
    function createFormByAlertID(id_alert){
      if(id_alert == "NO_CHOICE"){

        $(".activationCheck").attr('style', 'display: none;');
        clearForm();
        return;
      }
      $(".activationCheck").attr('style', 'display: block;');
      clearForm();
      var index = findIndex(id_alert, catalogJson);
      var catalog = catalogJson[index];
      var critere = JSON.parse(catalog.criteria);
      if(critere.type == "TRIGGER"){
        createDescriptionBox(catalog.description);
        createFrequency(critere.frequency);
        critere.frequency.forEach(function(value){
          createInputsCriteria(critere, value);
        });
        createDayRange(catalog.dayRange, "trigger");

      }
      else if (critere.type == "LIVE"){
        createDescriptionBox(catalog.description);
        createInputsCriteria(critere, frequency);
        createDayRange(catalog.dayRange, "live");
        $('#frequency').append('<label>'+trans("Choisissez votre occurence") +' : </label>&nbsp;&nbsp;');
        $('#frequency').append('<input type="checkbox" checked disabled />&nbsp;&nbsp;'+ trans("daily"));
        $("#nav_global_tab").css('display', 'block');
        $("#tabcontent_daily").addClass('active');
        $("#tab-daily").css('display', "block");

      }


    }

    function createInputsCriteria(criteria, frequency){
        // Criteria INPUTS
        if(criteria.type == "TRIGGER")
        {
          criteria.fields.forEach(function(value){
            createInput(value, frequency, criteria.fieldsType[value]);
          });
        }
        else
        {
          criteria.fields.forEach(function(value){

            createInputLive(value);
          });
        }
        // $("#frequency").append("<br><p>(*) Champs obligatoires</p>");
    }

      function createFrequency(frequency){
        $('#frequency').append('<label>Choisissez votre occurence : </label>&nbsp;&nbsp;');
        if(frequency.length > 1){
          frequency.forEach(function(value){
            $('#frequency').append('<label class="checkbox-inline dayRangemargin"><input type="checkbox" id="frequency-'+value+'" name="frequency-'+value+'" value="'+value+'" class="frequencyCheckBox">'+trans(value)+'</label>');
          });
        }
        else{
          $('#frequency').append('<input type="hidden" name="frequency-'+frequency+'" value="frequency-'+frequency+'"/>&nbsp;&nbsp;');
          $('#frequency').append('<input type="checkbox" checked disabled/>&nbsp;&nbsp;' + trans(frequency));
          $("#nav_global_tab").css('display', 'block');
          $("#tabcontent_"+ frequency).addClass('active');
          $("#tab-"+ frequency).css('display', "block");
          $('#tab-' + frequency).parent().addClass('active');
          console.log("need checked");
          if(frequency != "daily"){
            $("#tabcontent_daily").removeClass('active');
          }
        }

      }



  function createInput(critere, frequency, typeField){
    var form_group = $('<div class="form-group"></div>');
    var label = $('<label class="label col-sm-2 vcenter" style="color:gray;vertical-align:middle;display:table">' + trans(critere) + ' : <label/>');
    var row = $("<div class='row'></div>");
    var col = $('<div class="col-md-12"></div>')
    var inputgroup = $("<div class='input-group'></div>");
    $("#tabcontent_" + frequency).append(form_group);
    form_group.append(row);
    row.append(col);
    col.append(label);

    //createOperatorSelect(col, critere, frequency);
    col.append(inputgroup);
    inputgroup.append('<input type="text" name="'+frequency+'-' + critere +'" class="form-control form-control-no-width col-sm-4"/>');
    var symbol = typeField; // Name in database by default
    if(typeField == "currency")
      symbol = ",00€";
    else if (typeField == "percent")
      symbol = "%";
    else if (typeField == "credits")
      symbol = "credits";

    inputgroup.append('<span class="input-group-addon">'+ symbol +'</span>');
    //$("#form_container").append('<label class="label" style="color:gray"> Valeur : <label/><br>');

  }

  function createInputLive(critere){

    var form_group = $('<div class="form-group"></div>');
    var label = $('<label class="label col-sm-2 vcenter" style="color:gray;vertical-align:middle;display:table">' + trans(critere) + ' : <label/>');
    var row = $("<div class='row'></div>");
    var col = $('<div class="col-md-12"></div>')
    var inputgroup = $("<div class='input-group'></div>");
    $("#tabcontent_daily").append(form_group);
    form_group.append(row);
    row.append(col);
    col.append(label);

    createOperatorSelect(col, critere, frequency);
    col.append(inputgroup);
    inputgroup.append('<input type="text" name="' + critere +'" class="form-control form-control-no-width col-sm-4"/>');
    inputgroup.append('<span class="input-group-addon">,00 €</span>');
    //$("#form_container").append('<label class="label" style="color:gray"> Valeur : <label/><br>');

  }


  function createDayRange(dayRange, type){

    $('#tabcontent_daily').append('<label class="label" style="color:gray">'+trans("Activer le")+' : <br></span>');

    dayRange = JSON.parse(dayRange);
    dayRange.forEach(function(value){
      if(type == "live"){
        createCheckBox(value, trans(value), "live");
      }
      else{
        createCheckBox(value, trans(value), "trigger");
      }
    });
  }

  function createOperatorSelect(elem, critere, frequency){
    if(!frequency){
      $(elem).append('<select class="form-control form-control-no-width col-sm-6" style="margin-right: 10px;margin-bottom:10px;" name="'+frequency+'-operator-'+ critere + '" id="'+frequency+'-operator-'+ critere + '"><option value=">="> {{trans["higher_or_equal_to"]}} </option><option value="<=">{{trans["lower_or_equal_to"]}} </option><option value="==">{{trans["equal_to"]}} </option></select>');
    }
    else{
      $(elem).append('<select class="form-control form-control-no-width col-sm-6" style="margin-right: 10px;margin-bottom:10px;" name="operator-'+ critere + '" id="operator-'+ critere + '"><option value=">="> {{trans["higher_or_equal_to"]}} </option><option value="<=">{{trans["lower_or_equal_to"]}} </option><option value="==">{{trans["equal_to"]}} </option></select>');
    }

  }

  function createCheckBox(value, label, live){
      $('#tabcontent_daily').append('<label class="checkbox-inline dayRangemargin"><input type="checkbox" id="dateRange'+value+'" name="dateRange'+value+'" value="'+value+'">'+label+'</label>');
  }

  function createDescriptionBox(value){
    $('#form_container').append('<div class="callout bg-gray-light disabled color-palette" style="border-color: #00c0ef" id="desc_box"><p>'+value+'</p></div>');
  }

  function findIndex(id, array){
    for (var i = array.length - 1; i >= 0; i--) {
      if(array[i].id == id)
      {
        return i;
      }
    };
    return 0;
  }

  function clearForm(){
    $("#form_container").empty();
    $('#frequency').empty();
    $("#tabcontent_daily").empty();
    $("#tabcontent_monthly").empty();
    $("#tabcontent_weekly").empty();
    $(".nav-tabs-custom").css('display', 'none');
    $("#tab-monthly").css('display', 'none');
    $("#tab-daily").css('display', 'none');
    $("#tab-weekly").css('display', 'none');

  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  $('#newModal').on('hidden.bs.modal', function () {
    clearForm();
    $('#noChoice').prop('selected', true);
  });

  $('.editModalToggle').click(function(){
    var url = "{{url('notifications/editModal/')}}" + $(this).attr('id');
    $( "#editModal" ).empty();
    $( "body" ).addClass('loading');
    $.get(url , function( data ) {
      $( "#editModal" ).html( data );
      $( "body" ).removeClass('loading');

    });
  });

  $('body').on('click', '.frequencyCheckBox', function(){
    var target = $(this).attr('id').split('-')[1];
    var countCheck = $(".frequencyCheckBox:checked").length;
    if($(this).is(':checked')){
        $('.nav-tabs-custom').css('display', 'block');
        $('#tab-' + target).css('display', 'block');
        if(countCheck == 1){
          $('#tabcontent_' + target).addClass('active');
          $('#tab-' + target).parent().addClass('active');
        }
    } else {

        $('#tab-' + target).css('display', 'none');
        $('#tab-' + target).parent().removeClass('active');
        $('#tabcontent_' + target).removeClass('active');
        if(countCheck == 0){
          $('.nav-tabs-custom').css('display', 'none');
        }
        checkFirstActive();
    }


});

  $('body').on('click', '.update-frequencyCheckBox', function(){
    var target = $(this).attr('id').split('-')[1];
    var countCheck = $(".update-frequencyCheckBox:checked").length;
    if($(this).is(':checked')){
        $('.nav-tabs-custom').css('display', 'block');
        $('#tab-' + target).css('display', 'block');
        if(countCheck == 1){
          $('#tabcontent_' + target).addClass('active');
          $('#tab-' + target).parent().addClass('active');
        }
    } else {

        $('#tab-' + target).css('display', 'none');
        $('#tab-' + target).parent().removeClass('active');
        $('#tabcontent_' + target).removeClass('active');
        if(countCheck == 0){
          $('.nav-tabs-custom').css('display', 'none');

        }
        checkFirstActiveUpdate();
    }


});


function checkFirstActive(){
   var countCheck = $(".frequencyCheckBox:checked").length;
  console.log(countCheck);
  checkTabsShowing();
  $('.frequencyCheckBox').each(function(){
      var target = $(this).attr('id').split('-')[1];
      if($(this).is(':checked')){

        console.log(target);
        $('.nav-tabs-custom#updatetab').css('display', 'block');
        $('#tab-' + target).parent().addClass('active');
        $('#tabcontent_' + target).addClass('active');
        $('#tab-' + target).css('display', 'block');
        return false;
      }

  });
}

function checkFirstActiveUpdate(){

  var countCheck = $(".update-frequencyCheckBox:checked").length;
  console.log(countCheck);
  checkTabsShowingUpdate();
  $('.update-frequencyCheckBox').each(function(){
      var target = $(this).attr('id').split('-')[1];
      if($(this).is(':checked')){

        console.log(target);
        $('.nav-tabs-custom#updatetab').css('display', 'block');
        $('#update-tab-' + target).parent().addClass('active');
        $('#update-tabcontent_' + target).addClass('active');
        $('#update-tab-' + target).css('display', 'block');
        return false;
      }

  });

}


function checkTabsShowingUpdate(){
  $('.update-frequencyCheckBox:checked').each(function(){
    var target = $(this).attr('id').split('-')[1];
    if($('#update-tab-' + target).parent().hasClass('active')){
      console.log("active : ->");
      $('#update-tab-' + target).parent().removeClass('active');
      $('#update-tabcontent_' + target).removeClass('active');

    }

  });
}
function checkTabsShowing(){
  $('.frequencyCheckBox:checked').each(function(){
    var target = $(this).attr('id').split('-')[1];
    if($('#tab-' + target).parent().hasClass('active')){
      console.log("active : ->");
      $('#tab-' + target).parent().removeClass('active');
      $('#tabcontent_' + target).removeClass('active');

    }

  });
}

function trans(string){
  var language = "{{language}}";
  var translate = {
    "fr" : {
      monday : "Lundi",
      tuesday : "Mardi",
      wednesday : "Mercredi",
      thursday : "Jeudi",
      friday : "Vendredi",
      saturday : "Samedi",
      sunday : "Dimanche",
      "Activer le" : "Activer le",
      "Choisissez votre occurence" : "Choisissez votre occurence",
      "daily" : "Quotidien",
      "weekly" : "Hebdomadaire",
      "monthly" : "Mensuel",
      "nb_cdt" : "Nombre de crédits",
      "amount" : "Montant",

    },
    "en" : {
      monday : "monday",
      tuesday : "tuesday",
      wednesday : "wednesday",
      thursday : "thursday",
      friday : "friday",
      saturday : "saturday",
      sunday : "sunday",
      "Activer le" : "Activation days",
      "Choisissez votre occurence" : "Choose reccurence",
      "daily" : "Daily",
      "weekly" : "Weekly",
      "monthly" : "Monthly",
      "nb_cdt" : "Credits count",
      "amount" : "Amount"
    }

  };
  var response = translate[language][string];
  if(response && response != "undefined"){
    return response;
  }
  return string;
}

    //Add by mbamba on 14/03/2016 : Substitution of JS alert by Sweet Alert

    /**
     * Deletes the object designed by his id
     * @author Mory Bamba
     * @param {int}    id   the object id
     * @param {object} elem  the object where the user clicked on
     */
    function deleteNotification(id, elem) {
        var text = "", url = "";
        if (elem.hasClass('deletePopup')) {
            text = "{{ trans['notif delete confirm'] }}";
            url = "{{ url('notifications/delete')}}/" + id;
        } else if (elem.hasClass('deletePopupPhone')) {
            text = "{{ trans['device delete confirm'] }}";
            url = "{{ url('notifications/deletePoi')}}/" + id;
        }
        swal({
            title: "{{ trans['warning'] }}",
            text: text,
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            allowOutsideClick: false,
            confirmButtonText: "{{ trans['yes'] }}",
            cancelButtonText: "{{ trans['cancel'] }}",
            reverseButtons: true
        },
        function(isConfirm) {
            $('body').addClass('loading');
            if (isConfirm) {
                $.get(url)
                .done(function(data) {
                    window.location.reload();
                })
                .error(function(data) {
                    $('body').removeClass('loading');
                    swal("{{ trans['error'] }}", "{{ trans['on_error'] }}", "error");
                });
            } else {
                $('body').removeClass('loading');
            }
        });
    }

    $(document).on('click', '.deletePopup, .deletePopupPhone', function () {
        var id = $(this).attr("data-id");
        deleteNotification(id, $(this));
    });

</script>



