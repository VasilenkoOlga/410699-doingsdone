<?php

function template ($template_path, $data){
  if (file_exists($template_path)) {
    extract ($data);
    ob_start();
    require_once($template_path);
    return ob_get_clean();
  }
  return "";
}

function time_to_task ($task_date) {

    $current_time = time();
    $task_time_ts = strtotime($task_date);
    $seconds_day = 86400; 
    //$time_difference = floor(($task_time_ts - $current_time) / $seconds_day);
    $time_difference = ($task_time_ts - $current_time) / $seconds_day;
    if ($time_difference <= 1) {
      return true; 
    }
  else {
  return false;
  }
}
 
?>
