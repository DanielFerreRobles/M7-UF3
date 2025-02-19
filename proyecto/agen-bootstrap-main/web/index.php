<?php
require_once 'config.php';

$result = $mysqli->query("SELECT * FROM NEWS ORDER BY id DESC");
  print_r($result);