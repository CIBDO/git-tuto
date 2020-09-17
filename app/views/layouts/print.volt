 <!-- <body onload="window.print();"> -->
 <body>
 
    {{ content() }}

</body>
<script type="text/javascript">

    $(window).load(function() {
        $(".loader").fadeOut("slow");
    })

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