<?php


$statement = oci_parse($connection,
                "Select country_name,count(eventid) from ploya.event NATURAL JOIN ploya.city NATURAL JOIN ploya.country group by country_name");
  oci_execute($statement);
$result = "[['Country', 'No. of Attacks'],";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". str_replace("'","",$row[0]) ."',". $row[1]."],";
  }
  $result = substr_replace($result ,"", -1);
  $data = $result."]";
 

?>


<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['geochart'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
    });
    google.charts.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {
        var sqldata = <?php echo $data?>;
        
        var data = google.visualization.arrayToDataTable(sqldata);

        var options = {
            title: 'Number of Attack in a country',
            colors: ['#ec8f6e', '#f3b49f', '#f6c7b6',   

 

 
'#A00000', 
'#A80000', 
'#B00000', 
'#B80000', 
'#C00000', 
'#C80000',

'#D80000', 
'#E00000', 
'#E80000', 
'#F00000', 
'#F80000', 
'#FF0000', 

]
        };

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
    }
</script>
<div id="regions_div"></div>