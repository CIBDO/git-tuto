<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ trans["Approvisionnement"] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('index') }}"><i class="fa fa-dashboard"></i> {{ trans["Approvisionnement"]}}</a></li>
            <li class="active"><i class="fa fa-circl-o"></i> {{ trans['Gestion'] }}</li>
        </ol>
    </section>

    <?php $this->flash->output() ?>
   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12" >
                <div class="box box-primary">
                    <div class="box-body">
                        <form class="form-inline" role="form" action="" method="get">

                            <div class="row" style="margin-top : 10px">
                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date1">{{ trans['Du ']}} :</label>
                                        {{ dateField(['date1', 'class': 'form-control', 'id': 'date1']) }}
                                </div>

                                <div class="form-group  col-md-3" style="margin-right : 10px">
                                    <label for="date2">{{ trans['Au ']}} :</label>
                                    {{ dateField(['date2', 'class': 'form-control', 'id': 'date2']) }}
                                </div>

                                <div class="form-group  col-md-4">
                                    <button type="submit" class="btn btn-defaultx ajax-navigation pull-right" title="{{trans['Recherche']}}">
                                        <i class="fa fa-fw fa-filter"></i> {{trans['Filtrer']}}
                                    </button>
                                </div>
                            </div>  

                        </form>

                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main row -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">

                        <div id="toolbar" class="btn-group">
                        </div>

                        <div class="content">
                            <div class="table-responsive">
                                <table  id="table-javascript">
                                    <thead class="bg-aqua-gradient">
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

</div>

<script>
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(value, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function actionsFormatter(value, row, index) {
        return '<a class="btn btn-primary createDistribution" href="#" title="{{trans["distribuer ce produit"]}}" data-toggle="modal" data-target="#createDistribution" data-stockid="' + value + '" data-stockreste="' + row.reste + '">{{trans["distribuer"]}}</a><a class="btn btn-warning createAjustement" href="#" title="{{trans["Ajuster ce produit dans ce point de vente"]}}" data-toggle="modal" data-target="#createAjustement" data-stockid="' + value + '" data-stockreste="' + row.reste + '">{{trans["Ajuster"]}}</a>';
    }

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        var width = "200px"; //Width for the select inputs
        $("#merchants").select2({width: width});

        /*$('body').on('click', '.createDistribution', function () {
            $.ajax({
                url: "{{url('approvisionnement/createDistribution')}}/" + $(this).data('stockid') + "/" + $(this).data('stockreste'),
                cache: false,
                async: true
            })
            .done(function( html ) {
                $('#createDistribution').html(html);
            });
        });
*/
        $('body').on('click', '.deleteBtn', function () {
            var produit_id = $(this).data('produit');
            var currentTr = $(this).closest("tr");
            swal(
                    {
                        title: '{{ trans["Are you sure?"] }}',
                        text: "{{ trans['This action will delete the Produit named:'] }} " + $(this).data('produitname'),
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans["Yes, delete it!"] }}',
                        cancelButtonText: '{{ trans["No, cancel!"] }}',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm === true) {
                            $('body').addClass('loading');
                            $.ajax({
                                url: "{{url('produit/deleteProduit')}}/" + produit_id,
                                cache: false,
                                async: true
                            })
                            .done(function( result ) {
                                console.log(result);
                                $('body').removeClass('loading');
                                if(result == "1"){
                                    swal(
                                        '{{ trans["Deleted!"] }}',
                                        '{{ trans["The Produit has been successfully deleted."] }}',
                                        'success'
                                    );
                                    $(currentTr).css('background-color', '#ff9933').fadeOut(1000, function(){ $(this).remove();});
                                    $('body').addClass('loading');
                                    window.location.reload();
                                }
                                else{
                                    swal(
                                        '{{ trans["Cancelled!"] }}',
                                        "{{ trans['Cancelled'] }}",
                                        'error'
                                    );
                                }
                            });
                        } else if (isConfirm === false) {
                            
                        } else {
                        // Esc, close button or outside click
                        // isConfirm is undefined
                        }
                    }
            );
        });


        $('#table-javascript').bootstrapTable({
        data: {{ approvisionnements }},
        cache: false,
        striped: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 25, 50, 100, 200],
        sortOrder: "desc",
        sortName: "id",
        locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
        search: true,
        minimumCountColumns: 2,
        clickToSelect: false,
        toolbar: "#toolbar",
        showFooter: false,
        showExport: true,
        showPaginationSwitch: true,
        exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
        exportDataType : "selected",
        mobileResponsive: true,
        filterControl: true,
        rowStyle: rowStyle,
        columns: [
            {
                title: 'state',
                checkbox: true,
            },
            {
                field: 'motif',
                title: "{{ trans['Objet'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'date',
                title: "{{ trans['Date'] }}",
                sortable: true,
                filterControl: "input",
                formatter:date2Formatter
            },
            {
                field: 'point_distribution',
                title: "{{ trans['Point de distribution'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'produit_libelle',
                title: "{{ trans['Produit'] }}",
                sortable: true,
                filterControl: "input"
            },
            {
                field: 'quantite',
                title: "{{ trans['Quantite'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'lot',
                title: "{{ trans['Lot'] }}",
                sortable: true,
                filterControl: "select"
            },
            {
                field: 'date_peremption',
                title: "{{ trans['Date de peremption'] }}",
                formatter: date2OnlyFormatter,
                sortable: true,
                filterControl: "select"
            }
        ]
    });

    
        // Function located in opsise.js for modal form processing
        submitAjaxForm();


    });
</script>