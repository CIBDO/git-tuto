
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ get_title() }}s</title>


        {#
	        {{ stylesheet_link('css/target.css') }}
	        {{ javascript_include('assets/jQuery/jQuery-2.1.4.min.js') }} 
        #}

        {{ assets.outputCss('target_final_css') }} 
        {{ assets.outputJs('target_final_js') }}

        <meta name="author" content="Target Team">
        <link rel="icon" type="image/png" href="{{ url("img/target.png") }}" />
    </head>
        {{ content() }}

</html>
