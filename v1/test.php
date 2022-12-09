<?php
$d = strtotime("4/14/2018 1:00:00 AM", true);
echo $d.'<br>';

$datetime = new DateTime("4/14/2018 1:00:00 AM");
echo $datetime->format('U');


?>