<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>STRUKTUR ORGANISASI</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
</head>
<body>
    <a href="/home/admin">Kembali Ke Home</a>
    <div id="chart_div"></div>
</body>

<script>
    
    var json  =  {!! json_encode($json) !!};
    var petajabatan = json['original'];
    var count =  json['original'].length;
    
      google.charts.load('current', {packages:["orgchart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        // For each orgchart box, provide the name, manager, and tooltip to show.
        
        data.addRows(petajabatan);

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {'allowHtml':true});
      }
      //console.log(data, test);
 </script>
</html>