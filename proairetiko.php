<html>
<?php
  $con = mysqli_connect("localhost","root","","charts");
    if($con){
      echo "Επιτυχής Σύνδεση!";
    }
 ?>

  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type='text/javascript'>
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Esoda');
        data.addColumn('number', 'Eksoda');
        data.addRows([

          <?php
            $sql="SELECT * FROM etaireia";
            $fire=mysqli_query($con,$sql);
              while ($result=mysqli_fetch_assoc($fire)){
                          echo"[new Date(2020," .$result['ID'].", 1)," .$result['Esoda']."," .$result['Eksoda']."],";
              }
           ?>
        ]);

        var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

        var options = {
          displayAnnotations: true
        };

        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <div id='chart_div' style='width: 900px; height: 500px;'></div>
  </body>
</html>
