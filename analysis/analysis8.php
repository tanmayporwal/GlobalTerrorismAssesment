<?php
$year = $_GET['year'];

$statement = oci_parse($connection,
                "WITH tbl(grp_id, grp_name, cntry, numb) AS
                (SELECT e3.group_id, tg3.group_name, cc3.country_name, sum(e3.casualties)
                FROM ploya.event e3, ploya.terrorist_group tg3, ploya.city c3, ploya.country cc3
                WHERE e3.group_id=tg3.group_id AND e3.city_id=c3.city_id AND c3.country_id=cc3.country_id AND e3.event_date>=to_date('01-JAN-".$year."') AND e3.event_date<=to_date('31-DEC-".$year."')
                GROUP BY e3.group_id, tg3.group_name, c3.country_id, cc3.country_name)
                SELECT tt3.grp_name, tt1.cntry, sum(tt1.numb)
                FROM tbl tt1, (SELECT tt2.grp_id, tt2.grp_name, tt2.cntry FROM tbl tt2 WHERE tt2.numb = (SELECT max(tt4.numb) FROM tbl tt4 WHERE tt4.cntry=tt2.cntry AND tt4.grp_name != 'Unknown')) tt3
                WHERE tt1.cntry = tt3.cntry
                GROUP BY tt1.cntry, tt3.grp_id, tt3.grp_name
                ORDER BY sum(tt1.numb) DESC FETCH FIRST 10 ROWS ONLY");
  oci_execute($statement);
$result = "[";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['".str_replace("'","",$row[1])."','".$row[2]."','".str_replace("'","",$row[0])."'],";
  }
  $result = substr_replace($result ,"", -1);
  $data = $result."]";

?>
<script type="text/javascript">
      google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);
        function displayyear(){
            select = document.getElementById('year');
            for( i = 1970;i<=2017;i++) {
                select.add( new Option( i) );
            };
            document.getElementById("year").value = <?php echo $year ?>;
        }

        function getData(){
            sy = document.getElementById("year");
            year = sy.options[sy.selectedIndex].text;
            url = "analysis.php?analysis=8&year=" + year;
            window.location = url;
        }
      function drawTable() {
        var sqldata = <?php echo $data?>;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Country');
        data.addColumn('string', 'Nuber of Casualties');
        data.addColumn('string', 'Most Active Terror Group');
        data.addRows(sqldata);

        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '50%'});
      }

    </script>
     <div class="row" style="margin-top:50px">
        <div class="col-md-6">
            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" >
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-success" style="margin-top:25px" onclick="getData()">Ok</button>
        </div>
     </div>
     <?php 

echo '<script type="text/javascript">',
'displayyear();',
'</script>';
?>
    <div id="table_div" style="margin-top:50px"></div>