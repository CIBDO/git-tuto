
<div class="content-wrapper">

    <?php $this->flashSession->output() ?>

    <div class="content">
      <div class="row">
        {{ partial('notifications/listNotif')}}
      </div>
    </div>

</div>

<script type="text/javascript">
    

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
  </script>



