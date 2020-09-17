<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    {% include "print/entete.volt" %}

    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <input type="text" style="border: none; color: #000;font-size:18px;text-align: left;font-weight: bold; background: none;" name="titre" id="titre" size="80" value="Facture de demande de remboursement - Prestation">
          <small style="border: none; color: #000;font-size:18px;text-align: left;font-weight: bold; background: none;" class="pull-right">Date: {{ date(trans['date_only_format'], strtotime(dateFacture)) }}</small>
        </h2>
        <h3>
          <input type="text" style="border: none; color: #000;font-size:18px;text-align: left;font-weight: bold; background: none;" name="titre" id="titre" size="80" value="Doit: ----">
        </h3>
      </div><!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      
    </div><!-- /.row -->

    <!-- Table row -->
    <div class="row" style="margin-top: 15px">
      <div class="col-xs-12">
          <table  id="table-javascript">
              <thead class="bg-aqua-gradient">
              </thead>
              <tbody>
              </tbody>
          </table>
      </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-8">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          <textarea style="border: none; color: #000;font-size:18px;width: 100%;text-align: left;font-weight: bold; background: none;" rows="2">
          Arrêtée la présente facture à la somme de : {{ mtEnLettre }}
          </textarea>
          <div class="row">
              <div class="col-xs-6">
                <input type="text" style="border: none; color: #000;font-size:18px;text-align: left;font-weight: bold; background: none;" name="titre" id="titre" size="80" value="Pour Acquit">
              </div>
              <div class="col-xs-6">
                <input type="text" style="border: none; color: #000;font-size:18px;text-align: left;font-weight: bold; background: none;" name="titre" id="titre" size="80" value="La comptabilité">
              </div>
          </div>
          <img id="bcTarget" />
          <br /><br /><br />
        </p>
      </div><!-- /.col -->
      <div class="col-xs-4">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td align="right">{{ number_format(total,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Total/Assurés:</th>
              <td align="right">{{ number_format(assure,0,'.',' ') }} F CFA</td>
            </tr>
            <tr>
              <th>Total/Assureur:</th>
              <td align="right">{{ number_format(reste,0,'.',' ') }} F CFA</td>
            </tr>
          </table>
        </div>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- ./wrapper -->
  
<script type="text/javascript">
    function dateFormatter(value, row, index) {
        if (value) {
            return moment(new Date(value).getTime()/1000, "X").format("{{ trans['js_date_format'] }}");
        } else {
            return "-";
        }
    }

    function idFormatter(value, row, index) {
        return  value;
    }
    function cancelFormatter(value, row, index) {
        if(row.etat == 1){
            return '<a class="btn btn-warning btn-xs cancelTicket" data-ticketid="' + row.id + '"><i class="fa fa-trash pull-left"></i>Annuler</a>';
        }
        else{
            return "";
        }
    }

    function montant_patientFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_montant_patient, 10);
        }
        return numberFormatter(total);
    }
    function montant_normalFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_montant_normal, 10);
        }
        return numberFormatter(total);
    }
    function montant_restantFooter(data){
        var total = 0;
        for(var i = 0; i<data.length; i++){
            total = total + parseFloat(data[i].d_reste, 10);
        }
        return numberFormatter(total);
    }

    $( document ).ready(function() {
        //Hotfix Scrolling footer
        $('.fixed-table-body').scroll(function(){
            $('.fixed-table-footer').scrollLeft($(this).scrollLeft());
        });

        $('#table-javascript').bootstrapTable({
            data: {{ tickets }},
            cache: false,
            striped: true,
            pagination: false,
            pageSize: 10,
            pageList: [10, 25, 50, 100, 200],
            sortOrder: "desc",
            sortName: "id",
            locale: "{% if language == 'fr' %}fr-FR{% else %}en{% endif %}",
            search: false,
            minimumCountColumns: 2,
            clickToSelect: false,
            toolbar: "#toolbar",
            showLoading: true,
            showExport: true,
            showMultiSort: false,
            showPaginationSwitch: false,
            exportTypes: ['excel'],
            exportDataType : "selected",
            mobileResponsive: true,
            filterControl: false,
            showColumns: true,
            showFooter: false,
            rowStyle: rowStyle,
            columns: [
                {
                    title: 'state',
                    checkbox: true,
                },
                {
                    field: 'id',
                    title: "{{ trans['N° Ticket'] }}",
                    sortable: true,
                    filterControl: "input",
                    formatter: idFormatter,
                },
                {
                    field: 'date',
                    title: "{{ trans['Date  de création'] }}",
                    sortable: true,
                    formatter:date2Formatter
                },
                {
                    field: 'caissier_nom',
                    title: "{{ trans['Caissier'] }}",
                    sortable: true,
                    filterControl: "select"
                },
                {
                    field: 'patient_id',
                    title: "{{ trans['ID Patient'] }}",
                    sortable: true,
                    filterControl: "input"
                },
                {
                    field: 'patients_nom',
                    title: "{{ trans['Patient'] }}",
                    sortable: true,
                    filterControl: "input",
                },
                {
                    field: 'numero',
                    title: "{{ trans['N° Assuré'] }}",
                    sortable: true,
                    filterControl: "input",
                },
                {
                    field: 'a_unite',
                    title: "{{ trans['Unité'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'a_libelle',
                    title: "{{ trans['Prestation'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'd_quantite',
                    title: "{{ trans['Quantité'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'd_montant_normal',
                    title: "{{ trans['Montant de<br> la depense'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                },
                {
                    field: 'd_montant_difference',
                    title: "{{ trans['DIFF CELY'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                },
                {
                    field: 'assurance_taux',
                    title: "{{ trans['Taux'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'd_montant_patient',
                    title: "{{ trans['Part<br>Assuré'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                },
                {
                    field: 'd_reste',
                    title: "{{ trans['Part<br>Assureur'] }}",
                    sortable: true,
                    align: 'right',
                    filterControl: "input",
                    formatter: amountFormatter,
                }
            ]
        });

        $("div.fixed-table-toolbar").addClass("no-print");
        $("div.fixed-table-body").removeClass("fixed-table-body");

    });
</script>