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
    $time_difference = ($task_time_ts - $current_time) / $seconds_day;
    if ($time_difference <= 1) {
      return true; 
    }
  else {
  return false;
  }
}

function searchUserByEmail($link, $email) {
  if ($link && !empty($email)) {
    $query = "SELECT * FROM users WHERE `email` = '" . $email . "'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) !== 0) {
      return mysqli_fetch_assoc($result);
    }
  } 
  return false;
}

function count_task($task_table, $category){
  $count=0;
  if ($category=='Все'){
    $count=count($task_table);
  } else {
    foreach ($task_table as $val){
      if($val['category']==$category)
      $count++;
    } 
  }
  return $count;
}

function getUserProjects($link, $userId) {
  $projects = [];
  
  if ($link && intval($userId) > 0) {
    // выбираем все категори, доступные пользователю
    $projectsQuery = "SELECT id, name FROM projects WHERE `user_id` = '" . $userId . "'";
    $projectsResult = mysqli_query($link, $projectsQuery);

    if (intval(mysqli_num_rows($projectsResult)) > 0) {
      $projectsFetch = mysqli_fetch_all($projectsResult, MYSQLI_ASSOC);
      
      foreach ($projectsFetch as $project) {
        $projects[$project['id']] = [
          'id' => $project['id'],
          'name' => $project['name']
        ];
      }
    }
    
    $tasksQuery = "SELECT * FROM task_table WHERE `user_id` = '" . $userId . "'";
    $tasksResult = mysqli_query($link, $tasksQuery);
    
    // количество задач в категории
    if ($tasksResult !== false) {
      $tasks = mysqli_fetch_all($tasksResult, MYSQLI_ASSOC);
      
      if (!empty($projects)) {
        foreach ($projects as &$project) {
          $tasksCount = 0;
          
          if (!empty($tasks)) {
            foreach ($tasks as $task) {
              if (intval($project['id']) === intval($task['category'])) {
                $tasksCount++;
              }
            }
          }
          
          $project['tasks_count'] = $tasksCount;
        }
        unset($project);
      }
    }
  }
  
  return $projects;
}

function getUserTasks($link, $userId, $categoryId = false) {
  $tasks = [];
  
  if (isset($_REQUEST['tasks_filter'])) {
    if ($_REQUEST['tasks_filter'] == 'today') {
      $tasksQuery = "SELECT * FROM task_table WHERE date_deadline = CURDATE() AND `user_id` = '" . $userId . "'";
    } elseif ($_REQUEST['tasks_filter'] == 'tomorrow') {
      $tasksQuery = "SELECT * FROM task_table WHERE date_deadline = CURDATE() + 1 AND `user_id` = '" . $userId . "'";
    } elseif ($_REQUEST['tasks_filter'] == 'old') {
      $tasksQuery = "SELECT * FROM task_table WHERE date_deadline < CURDATE() AND `user_id` = '" . $userId . "'";
    }
  } else {
    // выбираем все задачи, доступные пользователю
    $tasksQuery = "SELECT * FROM task_table WHERE `user_id` = '" . $userId . "'";
  }
  
  $tasksResult = mysqli_query($link, $tasksQuery);
  
  if ($tasksResult !== false) {
    $tasks = mysqli_fetch_all($tasksResult, MYSQLI_ASSOC);
  }
  return $tasks;
}