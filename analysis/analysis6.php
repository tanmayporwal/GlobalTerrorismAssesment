<?php 
$start_year = $_GET['startyear'];
$end_year = $_GET['endyear'];
  $statement = oci_parse($connection,
                "SELECT e.iyear, sum(e.casualties) FROM ploya.event e
WHERE e.iyear>=".$start_year." AND e.iyear<=".$end_year."
GROUP BY e.iyear
ORDER BY e.iyear");
  oci_execute($statement);
$result = "[['Year', 'Casualties'],";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". $row[0] ."',". $row[1]."],";
  }
  $result = substr_replace($result ,"", -1);
  $data = $result."]";

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
            url = "analysis.php?analysis=6&startyear=" + start_year + "&endyear=" + end_year;
            window.location = url;
        }

        function drawChart() {

        var sqldata = <?php echo $data?>;
            var data = google.visualization.arrayToDataTable(sqldata);

            var options = {
                curveType: 'function',
                legend: {
                    position: 'bottom'
                },
                color: 'red'
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>

    <div class="row" style="margin-top:50px">
        <div class="col-md-5">
            <div class="form-group">
                <label for="startyear">Start Year:</label>
                <select class="form-control" id="startyear" >
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="endyear">End Year:</label>
                <select class="form-control" id="endyear">
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-success" style="margin-top:25px" onclick="getData()">Ok</button>
        </div>
    </div>


<?php 

echo '<script type="text/javascript">',
'displayyear('.$start_year.','.$end_year.');',
'</script>';
?>
<div id="curve_chart" style=" height: 500px;width: 1000px;margin-left:-100px"></div>