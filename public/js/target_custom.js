
$( document ).ready(function() {
    $("body").on("click", ".ajax-navigation", function(event) {
        $('body').addClass('loading');
    });
    setTimeout(function(){
        $(":input[required]").each(function(){
            var tmp = $(this).closest("div.form-group").find("label").append(' <span style="color:red">*</span>');
        });
    }, '1000');
});

function checkDate(e) {
    if($(this).find("input[name='startDate']").length) {
        var startDate = moment(e.date).format("x");
        var endDate = new Date($("input[name='endDate']").val().split("-").reverse().join("-")).getTime();
        if (startDate > endDate) {
            $("input[name='endDate']").parent().datepicker("setDate", moment(startDate, "x").format("DD-MM-YYYY"));
        }
    } else {
        var endDate = moment(e.date).format("x");
        var startDate = new Date($("input[name='startDate']").val().split("-").reverse().join("-")).getTime();
        if (startDate > endDate) {
            $("input[name='startDate']").parent().datepicker("setDate", moment(endDate, "x").format("DD-MM-YYYY"));
        }
    }
}

function submitAjaxForm() {
    $('body').on('submit', '.ajaxForm', function( event ) {
        // Stop form from submitting normally
        event.preventDefault();
        $('body').addClass('loading');

        // Get some values from elements on the page:
        var $form   = $(this),
            url     = $form.attr('action'),
            data    = $form.serialize();

        // Send the data using post
        var posting = $.post(url, data);

        // Put the results in a div
        posting.done(function( data ) {
            $('body').removeClass('loading');
            $( ".error_modal_container" ).empty().append( data );
            var msg = $(".error_modal_container .alert-success").html();
            //setTimeout(function() {
            if(data.indexOf('success') >= 0) {
            	$(".error_modal_container").closest("div.modal").modal('hide');
            	$( ".error_modal_container" ).empty();
                swal({
                        title: msg,
                        type: 'success',
                        showCloseButton: true,
                        confirmButtonClass: 'btn btn-warning',
                        cancelButtonClass: 'btn btn-danger',
                        confirmButtonText:'OK',
                }).then(function() {
                    $('body').addClass('loading');
                    window.location.reload();        
                }, function(dismiss) {
                    $('body').addClass('loading');
                    window.location.reload(); 
                });
            }
            //}, 1000);
        });
    });

    $('body').on('submit', '.ajaxFormNoreload', function( event ) {
        // Stop form from submitting normally
        event.preventDefault();
        $('body').addClass('loading');

        // Get some values from elements on the page:
        var $form   = $(this),
            url     = $form.attr('action'),
            data    = $form.serialize();

        // Send the data using post
        var posting = $.post(url, data);

        // Put the results in a div
        posting.done(function( data ) {
            $('body').removeClass('loading');
            $( ".error_modal_container" ).empty().append( data );
            setTimeout(function() {
                if(data.indexOf('success') >= 0) {
                    $('body').addClass('loading');
                    $( ".error_modal_container" ).empty();
                    $('.modal').modal('hide');
                    $('.modal-dialog').remove();
                    if ($("form[role='form']").length) {
                        $("form[role='form']").submit();
                    } else {
                        //window.location.reload();
                    }
                }
            }, 1000);
        });
    });
}
