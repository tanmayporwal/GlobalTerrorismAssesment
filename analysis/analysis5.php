<?php 
$country = $_GET['country'];
  $statement = oci_parse($connection,
                "SELECT t.target_name, count(*)*100/(SELECT count(*) FROM ploya.event ee, ploya.city cc1, ploya.country cc2 WHERE ee.target_id IS NOT NULL AND ee.city_id=cc1.city_id AND cc1.country_id=cc2.country_id AND cc2.country_name='".$country."')
                FROM ploya.target t, ploya.event e, ploya.city c1, ploya.country c2
                WHERE e.target_id=t.target_id AND e.city_id=c1.city_id AND c1.country_id=c2.country_id AND c2.country_name='".$country."'
                GROUP BY t.target_name");
  oci_execute($statement);
$result = "[['Target Group', 'Attack Percentage'],";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". $row[0] ."',". $row[1]."],";
  }
  $result = substr_replace($result ,"", -1);
  $data = $result."]";

$statement = oci_parse($connection,"SELECT country_name from ploya.country ORDER BY COUNTRY_NAME");
oci_execute($statement);
$country_list = array();
  while (($row = oci_fetch_array($statement))) {
        array_push($country_list, strval($row[0]));
  }
?>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
    function getData(){
         c = document.getElementById("country");
                country = c.options[c.selectedIndex].text;
                url = "analysis.php?analysis=5&country=" + country;
                window.location = url;
    }

      function drawChart() {
        var sqldata = <?php echo $data?>;
        var data = google.visualization.arrayToDataTable(sqldata);

        var options = {
          width: 800
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
</script>
<div class="row" style="margin-top:50px">
        <div class="col-md-6">
            <div class="form-group">
                <label for="country">Select Country:</label>
                <select class="form-control" id="country">
                    <?php 
                        for($i=0; $i<count($country_list); $i++) {
                            if($country_list[$i] == $country){
                                echo "<option selected>".$country_list[$i]."</option>";
                            }else{
                                echo "<option>".$country_list[$i]."</option>";
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

<div id="piechart" style=" height: 500px;margin-top:10px"></div>