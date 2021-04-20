<?php
$terror_group = $_GET['terrorgroup'];

$statement = oci_parse($connection,
                "select countrY_nAME,
100*COUNT(EVENTID)/(SELECT COUNT(EVENTID) from PLOYA.event NATURAL JOIN PLOYA.terrorist_group WHERE GROUP_NAME='".$terror_group."')
from PLOYA.event natural join PLOYA.city NATURAL JOIN PLOYA.country NATURAL JOIN PLOYA.terrorist_group WHERE GROUP_NAME='".$terror_group."' GROUP BY COUNTRY_NAME");
  oci_execute($statement);
$result = "[['Country', 'Percentage of Terror Attack '],";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". $row[0]."',". $row[1]."],";
  }
  $result = substr_replace($result ,"", -1);
  $data = $result."]";

  $statement = oci_parse($connection,"SELECT GROUP_NAME from PLOYA.terrorist_group ORDER BY GROUP_NAME");
oci_execute($statement);
$terror_group_list = array();
  while (($row = oci_fetch_array($statement))) {
        array_push($terror_group_list, strval($row[0]));
  }
  
?>

<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

        function displayterrorgroup(){
            document.getElementById("terror_group").value = '<?php echo $terror_group ?>';
        }

      function getData(){
            terrorgroup = document.getElementById("terror_group");
            terror_group = terrorgroup.options[terrorgroup.selectedIndex].text;
            url = "analysis.php?analysis=3&terrorgroup=" + terror_group;
            window.location = url;
        }

      function drawChart() {
        var sqldata = <?php echo $data?>;
        var data = google.visualization.arrayToDataTable(sqldata);

        var options = {
          legend: 'none',
          pieSliceText: 'label',
          slices: {  2: {offset: 0.2},
                    4: {offset: 0.3},
                    8: {offset: 0.4},
                    12: {offset: 0.5},
          },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>

    <div class="row" style="margin-top:50px">
        <div class="col-md-6">
            <div class="form-group">
                <label for="terror_group">Terror Group:</label>
                <select class="form-control" id="terror_group">
                     <?php 
                        for($i=0; $i<count($terror_group_list); $i++) {
                            if($terror_group_list[$i] == $terror_group){
                                echo "<option selected>".$terror_group_list[$i]."</option>";
                            }else{
                                echo "<option>".$terror_group_list[$i]."</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-success" style="margin-top:25px" onclick="getData()">Ok</button>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <div class="col-md-6">
            <h4>No of Fatalities Caused: </h4>
            <h4>Overall Economic Impact: </h4>
        </div>
    </div>
    <?php 

echo '<script type="text/javascript">',
'displayterrorgroup();',
'</script>';
?>
    <div id="piechart" style="height: 500px;"></div>