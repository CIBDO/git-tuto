
<body class="hold-transition skin-blue sidebar-mini bo-body">
    <div class="wrapper">

        <header class="main-header">

            <!-- Logo -->
            <a href="{{ url("") }}" class="logo ajax-navigation">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <!-- <img id="logo-menu" width="100%" src="{{ url("img/logo.png") }}" alt="logo"> -->
                {{stucture_name_config}}
                <!-- <div id="logo-menu"></div> -->
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" id="sidebartoggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Notifications -->
                        <li class="dropdown notifications-menu" id="notif_container_box">
                            {# 
                                {{ partial("notifications/setNotificationsRead") }}
                            #}
                        </li>


                        <li class="dropdown notifications-menu" style="padding-right: 5px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
                                {{ language }} 
                                <i class="glyphicon glyphicon-triangle-bottom" style="font-size:10px;">
                                </i>
                            </a>
                            <ul class="dropdown-menu" id="dropdown-menu-languages">
                                <li id="li-container-languages">
                                    <!-- inner menu: contains the actual data -->
                                    <ul id="ul-languages" class="menu">
                                        <li id="li-language-en" class="li-language">
                                                <img id="logo-en" class="logo-flag-language" src="{{ url ("img/flags/England.png")}}" alt="en">
                                                <?php echo $this->tag->linkTo(array("index/enLanguage", "english", "class" => "alangue ajax-navigation"));?>
                                        </li>
                                        <li id="li-language-fr" class="li-language">
                                            <img id="logo-fr" class="logo-flag-language" src="{{ url ("img/flags/France.png")}}" alt="fr">
                                                <?php echo $this->tag->linkTo(array("index/frLanguage", "français", "class" => "alangue ajax-navigation"));?>
                                        </li>

                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown notifications-menu" style="padding-right: 5px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user" style="font-size:10px;"></i>
                            </a>
                            <ul class="dropdown-menu" id="dropdown-menu-user" style="overflow: hidden;">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="{{ url ("account/index")}}" class="ajax-navigation">
                                                <i class="fa fa-user"></i> {{ trans['My profile'] }}
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="#" class="ajax-navigation">
                                                <i class="fa fa-question"></i> {{ trans['Support'] }}
                                            </a>
                                        </li> -->
                                        <li>
                                            <a href="{{ url ("authen/logout")}}" class="ajax-navigation" id="sessionlogout">
                                                <i class="fa fa-key"></i> {{ trans['Log out'] }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>

                </div>

            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu ">
                    {{ elements.getSidebarMenu(trans) }}
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <div id="global_ajax_container">
            {{ content() }}
        </div>

        <footer class="main-footer">
            <span class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </span>
            <span><strong>Copyright &copy; 2020 <a href="#">IC4D</a>.</strong> Tous droits réservés .</span>
        </footer>

        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->
    <!-- BEGIN JAVASCRIPTS -->
    <!-- Load javascripts at bottom, this will reduce page load time -->
    
    
    <script type="text/javascript">

    $(window).load(function() {
        $(".loader").fadeOut("slow");
    })

   $(document).on("click", '#allRead', function(event) {
            // Ajax request
            {% if notificationsIDLists is defined %}
                var list = "{{notificationsIDLists}}";
            {% else %}
                var list = "";
            {% endif %}
            $.ajax({
              url: "{{url('notifications/setNotificationsRead/')}}"+list,
            }).done(function(data) {

              $('#notif_container_box').html(data);
            });
        });
    $(document).on("click", '.readOnce', function(event) {

            // Ajax request

            var id = $(this).attr('id');
            var ctrlName = "{{controllerName}}";
            if(ctrlName != "notifications"){
                $("body").addClass("loading");
            }
            $.ajax({
              url: "{{url('notifications/setNotificationsRead/')}}" + id,
            }).done(function(data) {
                console.log(data);
              $('#notif_container_box').html(data);
              window.location = "{{ url('notifications/index')}}"+"#"+id;
            });
    });

    function date2Formatter(value, row, index) {
        if (value) {
            return moment(new Date(value).getTime()/1000, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function date2OnlyFormatter(value, row, index) {
        if (value) {
            return moment(new Date(value).getTime()/1000, "X").format("{{ trans['js_date_only_format'] }}");
        } else {
            return "-";
        }
    }

    function rowStyle(row, index) {
        var classes = ['info'];
        if (index % 2 === 0) {
            return {
                classes: classes[0]
            };
        }
        return {};
    }

    //On any bootstrap table, check all when check button cliked export
    $('body').on('click', "input[name='btSelectAll']", function () {   
        var checked = $(this).prop("checked");
        var currrentTable = $(this).closest("table");
        var data = currrentTable.bootstrapTable('getData');
        checked = checked;
        for (var i = data.length - 1; i >= 0; i--) {
            if(checked == true){
                $('#table-javascript').bootstrapTable('check', i);
                $(this).prop('checked', true);
            }
            else{
                $('#table-javascript').bootstrapTable('uncheck', i);
                $(this).prop('checked', false);
            }
        };
    });

    //On any bootstrap table, prevent export
    $('body').on('click', ".bootstrap-table div.export button", function () {   
        var currrentTable = $(this).closest(".bootstrap-table");
        var selectedRows = $(' table', currrentTable).bootstrapTable('getSelections', null);
        if(selectedRows.length <= 0){
            sweetAlert("Oops...", "Vous devez selectionner au moins une ligne", "error");
            return false;
        }
    });

    function amountFormatter(value, row, index) {
        //var options = {style: "currency", currency: "XOF"};
        var options = {};
        var numberFormat = new Intl.NumberFormat("fr-FR", options);
        return [
           numberFormat.format(value)
        ].join('');
    }

    function numberFormatter(value = 0) {
        //var options = {style: "currency", currency: "XOF"};
        var options = {};
        var numberFormat = new Intl.NumberFormat("fr-FR", options);
        return numberFormat.format(value);
    }


    
    </script>
    <!-- END JAVASCRIPTS -->

</body>

