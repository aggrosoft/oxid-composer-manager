<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>AGCMS Manager</title>

    <!-- Bootstrap -->
    <link href="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" type="text/css" href="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/css/ripples.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.12/b-1.2.2/datatables.min.css"/>

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container-fluid">

    <div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="javascript:void(0)"><i class="material-icons">receipt</i> [{oxmultilang ident="mxsnwbilling"}]</a>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <form class="navbar-form navbar-right form-inline">
                    <div class="form-group is-empty">
                        <input id="date-input" type="month" class="form-control" value="[{$curdate}]">
                    </div>
                </form>
                <span class="navbar-text navbar-right">
                    [{oxmultilang ident="SHIRTNETWORK_BILLING_MONTH_YEAR"}]
                </span>
            </div>
        </div>
    </div>

    <div class="well">
        <table id="billing-table" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_ORDERNO"}]</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_CUSTOMER"}]</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_ORDERTOTAL"}]</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_COMMISSION_OVER_TEN"}]</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_COMMISSION_UNDER_TEN"}]</th>
                <th>[{oxmultilang ident="SHIRTNETWORK_BILLING_COMMISSION_SUM"}]</th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">[{oxmultilang ident="SHIRTNETWORK_BILLING_COMMISSION_SUM_FULL"}]: </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/b-1.2.2/datatables.min.js"></script>
<script src="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/js/bootstrap.min.js"></script>
<script src="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/js/material.min.js"></script>
<script src="[{ $oViewConf->getModuleUrl('shirtnetwork') }]out/src/js/ripples.min.js"></script>
<script type="text/javascript">
  $(function() { //DOM Ready

    var sumTotal = 0;
    var sumUnder = 0;
    var sumOver = 0;

    var table = $('#billing-table').DataTable({
      "ajax": {
        "url": '[{ $oViewConf->getSelfLink()|html_entity_decode }]&cl=shirtnetworkbilling&fnc=getajaxbilling',
        "data": function (d) {
          d.search.date = $("#date-input").val();
          d.search.query = $("#billing-search-input").val();
          return d;
        },
        "dataSrc": function (d) {
          sumTotal = d.sumTotal;
          sumUnder = d.sumUnder;
          sumOver = d.sumOver;
          return d.data;
        }
      },
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api();
        var total = sumTotal.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });
        var totalUnder = sumUnder.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });
        var totalOver = sumOver.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });

        $( api.column( 6 ).footer() ).html(
          total
        );
        $( api.column( 5 ).footer() ).html(
          totalOver
        );
        $( api.column( 4 ).footer() ).html(
          totalUnder
        );
      },
      "columnDefs": [
        {
          targets: [ 0 ],
          visible: false,
          searchable: false
        },
        {
          targets: [3,4,5,6],
          render: function (data, type, row) {
            return Number(data).toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });
          }
        }
      ],
      "processing": true,
      "serverSide": true,
      "initComplete": function (settings, json) {
        $.material.init();
        initCustomTableElements();
      },
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/German.json"
      }
    });

    function initCustomTableElements() {
      $('#date-input, #billing-search-input').change(function () {
        table.draw();
      })
    }
  });

</script>

</body>
</html>