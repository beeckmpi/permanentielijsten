<?php header("Content-type: application/x-ms-download");
header("Content-Disposition: attachment; filename=".$filename);
header('Cache-Control: public'); ?>