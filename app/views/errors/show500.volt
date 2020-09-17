{{ content() }}
<div class="content-wrapper">
<div class="container">
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
	<div class="error-page">
        <h2 class="headline text-yellow">500</h2>

        <div class="error-content">
          	<h1><i class="fa fa-warning text-yellow"></i>&nbsp;&nbsp;{{ trans['internal_server_error']}}</h1>
		    <p>{{ trans['internal_server_error_desc']}}</p>
		    <p>{{ link_to('index', trans['back_to_home'], 'class': 'btn btn-primary form-control','title': trans['back_to_home']) }}</p> 
        </div>
        <!-- /.error-content -->
      </div>
    
</div>
</div>