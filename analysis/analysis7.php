<?php
$year = $_GET['year'];

$statement = oci_parse($connection,
                "SELECT e.event_date,e.addnotes, c1.city_name, c2.country_name,  e.casualties, tg.group_name
                FROM ploya.Event e, ploya.terrorist_group tg, ploya.city c1, ploya.country c2
                WHERE e.group_id = tg.group_id AND e.city_id = c1.city_id AND c1.country_id = c2.country_id AND e.addnotes IS NOT NULL AND
                e.event_date>=to_date('01-JAN-".$year."') AND e.event_date<=to_date('31-DEC-".$year."') AND e.casualties IN (
                SELECT tbl.casualties FROM
                (SELECT * FROM ploya.Event e2
                WHERE e2.event_date>=to_date('01-JAN-".$year."') AND e2.event_date<=to_date('31-DEC-".$year."') AND e2.casualties IS NOT NULL AND e2.addnotes IS NOT NULL
                ORDER BY e2.casualties DESC) tbl
                WHERE ROWNUM<=10)
                ORDER BY e.casualties DESC");
  oci_execute($statement);
$result = "[";
  while (($row = oci_fetch_array($statement))) {
    $result = $result."['". $row[0] ."','".  str_replace("'","",$row[1])."','". $row[2]."\, ".$row[3]."','". $row[4]."','". str_replace("'","",$row[5])."'],";
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
            url = "analysis.php?analysis=7&year=" + year;
            window.location = url;
        }

      function drawTable() {
        var sqldata = <?php echo $data?>;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Event Date');
        data.addColumn('string', 'Summary');
        data.addColumn('string', 'Location');
        data.addColumn('string', 'Casualties');
        data.addColumn('string', 'Terrorist Group');
        data.addRows(sqldata);
        /*data.addRows([
          ['Mike',  {v: 10000, f: '$10,000'}, true],
          ['Jim',   {v:8000,   f: '$8,000'},  false],
          ['Alice', {v: 12500, f: '$12,500'}, true],
          ['Bob',   {v: 7000,  f: '$7,000'},  true]
        ]);*/

        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
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