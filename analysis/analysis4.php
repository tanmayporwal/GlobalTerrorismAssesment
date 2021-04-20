<?php 
$country = $_GET['country'];
  $statement = oci_parse($connection,
                "select p.attack_name,total,OnCountry from
(select count(eventid) as total,attack_name from ploya.event natural join ploya.attack group by attack_name) p inner join
(select count(eventid) as OnCountry,attack_name,country_name from ploya.Attack natural join ploya.event natural join ploya.city natural join ploya.country where country_name='".$country."' group by attack_name,country_name) q
on p.attack_name=q.attack_name");
  oci_execute($statement);
$result = "[['Attack Type', 'Country','Global'],";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". $row[0] ."','". $row[2]."','". $row[1]."'],";
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
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);
function getData(){
         c = document.getElementById("country");
                country = c.options[c.selectedIndex].text;
                url = "analysis.php?analysis=4&country=" + country;
                window.location = url;
    }

    function drawChart() {
        var sqldata = <?php echo $data?>;
    var data = google.visualization.arrayToDataTable(sqldata);

    var options = {
        chart: {
        }
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
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

<div id="columnchart_material" style=" height: 500px;margin-top:50px"></div>