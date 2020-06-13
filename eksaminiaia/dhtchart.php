<?php
//index.php
$connect = mysqli_connect("localhost", "arduino", "1234", "arduino_test");
$query = '
SELECT temperature,humidity,
UNIX_TIMESTAMP(CONCAT_WS(" ", date)) AS datetime
FROM dht11
ORDER BY date DESC
';

$result1 = mysqli_query($connect, $query);
$result2 = mysqli_query($connect, $query);

$rows = array();
$table = array();

$table['cols'] = array(
 array(
  'label' => 'Date Time',
  'type' => 'datetime'
 ),
 array(
  'label' => 'Temperature (°C)',
  'type' => 'number'
 )
);

$table1['cols'] = array(
 array(
  'label' => 'Date Time',
  'type' => 'datetime'
 ),
 array(
  'label' => 'Humidity (%)',
  'type' => 'number'
 )
);

while($row = mysqli_fetch_array($result1))
{
 $sub_array = array();
 $datetime = explode(".", $row["datetime"]);
 $sub_array[] =  array(
     "v" => 'Date(' . $datetime[0] . '000)'
     );
 $sub_array[] =  array(
      "v" => $row["temperature"]
     );

 $rows[] =  array(
     "c" => $sub_array
    );
}

 while($row1 = mysqli_fetch_array($result2))
{
 $sub_array = array();

 $datetime = explode(".", $row1["datetime"]);
 $sub_array[] =  array(
     "v" => 'Date(' . $datetime[0] . '000)'
     );

  $sub_array[] =  array(
      "v" => $row1["humidity"]
      );

 $rows1[] =  array(
     "c" => $sub_array
    );
}



$table['rows'] = $rows;
$table1['rows'] = $rows1;

$jsonTable = json_encode($table);
$jsonTable1 = json_encode($table1);

?>

<html>
 <head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript"> src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script type="text/javascript">
   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(drawChart1);
   function drawChart1()
   {
   var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);


   var options = {
     title: 'Values in Celsius',
     curveType: 'function',
     legend: { position: 'bottom' }
   };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart1'));

    chart.draw(data, options);

   }
  </script>

  <script type="text/javascript">
   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(drawChart2);
   function drawChart2()
   {

   var data1 = new google.visualization.DataTable(<?php echo $jsonTable1; ?>);


   var options1 = {
     title: 'Values in percent',
     curveType: 'function',
     legend: { position: 'bottom' }
   };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));

    chart.draw(data1, options1);

   }

  </script>

  <style>
  .page-wrapper
  {
   width:1000px;
   margin:0 auto;
  }
  </style>
 </head>
 <body>
  <div class="page-wrapper">
    <h3 align="center">ΕΞΑΜΗΝΙΑΙΑ ΕΡΓΑΣΙΑ ΣΤΟ ΜΑΘΗΜΑ ΕΦΑΡΜΟΓΕΣ ΤΟΥ ΔΙΑΔΙΚΤΥΟΥ ΣΤΗΝ ΠΑΡΑΓΩΓΗ </h3>
    <br />

    <h3 align="center">Ονοματεπώνυμο: Στέργιος Τούσουλης  Α.Μ:45823</h3>
    <br />
  
    <h3 align="center">ΤΙΤΛΟΣ ΕΡΓΑΣΙΑΣ: ΚΑΤΑΓΡΑΦΗ ΚΑΙ ΑΠΕΙΚΟΝΗΣΗ ΜΕΤΡΗΣΕΩΝ ΘΕΡΜΟΚΡΑΣΙΑΣ Κ' ΥΓΡΑΣΙΑΣ ΣΕ ΠΡΑΓΜΑΤΙΚΟ ΧΡΟΝΟ</h3>

   <br />
   <br />
   <br />
   <h2 align="center">DHT22 Sensor Real Time Data Line Charts</h2>
   <div id="curve_chart1" style="position: center width: 70%; height: 300px"></div>

   <div id="curve_chart2" style="position: center width: 70%; height: 300px"></div>

  </div>


 </body>
</html>
