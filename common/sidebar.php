<?php

$statement = oci_parse($connection,
                "select distinct country_name,motive,event_date,group_name  from ploya.event natural join ploya.city natural join ploya.country natural join
ploya.terrorist_group where (extract(month from event_date)=(select extract(month from sysdate) from dual)) and (extract(day from event_date) = (select extract(day from sysdate) from dual)) AND MOTIVE IS NOT NULL AND EVENT_DATE IS NOT NULL AND MOTIVE!='Unknown' ORDER BY event_date DESC");
  oci_execute($statement);
    $event_list=array(); //array that stores each row
  $event=array();
  while (($row = oci_fetch_array($statement))) {
          array_push($event, strval($row[0]), strval($row[1]), strval($row[2]), strval($row[3])); //add as many columns are there in select statement
      array_push($event_list, $event);
      $event=array();
  }

      $random_index = array_rand($event_list, 3);
?>

<div style="border: solid black 1px;">
    <h2 style="text-align:center"> This Day</h2>
    <div class="panel-group">
        <div class="panel panel-default">
            <?php
                echo "<div class=\"panel-heading\" ><strong>Event Date : </strong> ".$event_list[$random_index[0]][2]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\" ><strong>Country : </strong>".$event_list[$random_index[0]][0]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\"><strong>Event Summary : </strong>".$event_list[$random_index[0]][1]."</div>";
                echo "<div class=\"panel-body\" ><strong>Terror Group : </strong>".$event_list[$random_index[0]][3]."</div>";
            ?>
        </div>
        <div class="panel panel-default">
            <?php
                echo "<div class=\"panel-heading\"><strong>Event Date : </strong> ".$event_list[$random_index[1]][2]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\"><strong>Country : </strong>".$event_list[$random_index[1]][0]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\"><strong>Event Summary : </strong>".$event_list[$random_index[1]][1]."</div>";
                echo "<div class=\"panel-body\" ><strong>Terror Group : </strong>".$event_list[$random_index[1]][3]."</div>";
            ?>
        </div>
        <div class="panel panel-default">
            <?php
                echo "<div class=\"panel-heading\"><strong>Event Date : </strong> ".$event_list[$random_index[2]][2]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\" ><strong>Country : </strong>".$event_list[$random_index[2]][0]."</div>";
                echo "<div class=\"panel-body\" style=\"margin-bottom:-20px;\"><strong>Event Summary : </strong>".$event_list[$random_index[2]][1]."</div>";
                echo "<div class=\"panel-body\" ><strong>Terror Group : </strong>".$event_list[$random_index[2]][3]."</div>";
            ?>
        </div>
    </div>
</div>