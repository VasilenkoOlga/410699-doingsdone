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
?>

