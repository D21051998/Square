<?php
$d = new DateTime('next Thursday');
$tomorrow = $d->format('d/m/Y h.i.s');
echo $tomorrow;

?>