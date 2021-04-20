<?php
$start_year = $_GET['startyear'];
    $end_year = $_GET['endyear'];
    $country = $_GET['country'];
    

$statement = oci_parse($connection,
                "select iyear,overall,country from
(SELECT iyear,(COUNT(*)/(select count(*) from PLOYA.country)) as overall FROM ploya.EVENT where iyear>=".$start_year." and iyear<=".$end_year." GROUP BY 
iyear order by iyear) a LEFT JOIN
(select extract(year from event_date) iyear1 ,count(eventid) as country from ploya.Event natural join ploya.city
natural join PLOYA.country where country_name='".$country."'
group by extract(year from event_date) order by extract(year from event_date)) b ON a.iyear = b.iyear1 order by a.iyear
");
  oci_execute($statement);
$result = "[";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."[". $row[0].",". $row[1].",";
    $second_output = 0;
    if ($row[2]!=''){
       $second_output = $row[2];
    }
    $result = $result. $second_output."],";
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
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function displayyear(startyear,endyear){
            select = document.getElementById('startyear');
            for( i = 1970;i<=2007;i++) {
                select.add( new Option( i) );
            };
            select = document.getElementById('endyear');
            for( i = startyear;i<=2017;i++) {
                select.add( new Option( i) );
            };
            document.getElementById("startyear").value = startyear;
            document.getElementById("endyear").value = endyear;
        }

        function changeEndyear(){
            startyear = document.getElementById('startyear').value;
            alert(startyear);
            $('#endyear')
                .find('option')
                .remove()
            ;
            select = document.getElementById('endyear');
            for( i = startyear+1;i<=2017;i++) {
                select.add( new Option( i) );
            };
        }
        function getData(){
            sy = document.getElementById("startyear");
            start_year = sy.options[sy.selectedIndex].text;
            ey = document.getElementById("endyear");
            end_year = ey.options[ey.selectedIndex].text;
            c = document.getElementById("country");
            country = c.options[c.selectedIndex].text;
            url = "analysis.php?analysis=2&startyear=" + start_year + "&endyear=" + end_year + "&country=" + country;
            window.location = url;
        }
        function drawChart() {
            var data = new google.visualization.DataTable();

            var sqldata = <?php echo $data?>;
      data.addColumn('number', 'X');
      data.addColumn('number', 'Global Average');
      data.addColumn('number', 'Country');

      data.addRows(sqldata);

      var options = {
        hAxis: {
          title: 'Years'
        },
        vAxis: {
          title: 'Number of Attacks'
        },
        colors: ['#AB0D06', '#007329']
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
    </head>
    <div class="row" style="margin-top:50px">
        <div class="col-md-6">
            <div class="form-group">
                <label for="startyear">Start Year:</label>
                <select class="form-control" id="startyear" >
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="endyear">End Year:</label>
                <select class="form-control" id="endyear">
                </select>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:20px">
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

<?php 

echo '<script type="text/javascript">',
'displayyear('.$start_year.','.$end_year.');',
'</script>';
?>
<div id="chart_div" style=" height: 500px;width: 900px;margin-left:-100px"></div>