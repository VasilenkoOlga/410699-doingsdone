<?php
require_once('function.php');

// показывать или нет выполненные задачи

$show_complete_tasks = 1;


$projects = [
	"Все", 
	"Входящие", 
	"Учеба", 
	"Работа", 
	"Домашние дела", 
	"Авто"
];

$task_table = [
    [ 
        "task" => "Собеседование в IT компании",
		"date" => "11.02.2018",
		"category" => "$projects[3]",
		"realization" => false
    ],
    [ 
		"task" => "Выполнить тестовое задание",
		"date" => "25.05.2018",
		"category" => "$projects[3]",
		"realization" => false
		],
    [ 
		"task" => "Сделать задание первого раздела",
		"date" => "21.04.2018",
		"category" => "$projects[2]",
		"realization" => true
    ],
	[ 
		"task" => "Встреча с другом",
		"date" => "22.04.2018",
		"category" => "$projects[1]",
		"realization" => true
    ],
	[ 
		"task" => "Купить корм для кота",
		"date" => "22.04.2018",
		"category" => "$projects[4]",
		"realization" => false
    ],
	[ 
		"task" => "Заказать пиццу",
		"date" => "11.02.2018",
		"category" => "$projects[4]",
		"realization" => false
    ],
];

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


if (isset($_GET["show_completed"])) {
  if(isset($_COOKIE["showcompl"])) {
    $show_complete_tasks = ($_COOKIE["showcompl"]== 1) ? 0 : 1;
  }
  setcookie("showcompl", $show_complete_tasks, strtotime("+30 days"), "/");
  header("Location: /");
}

$showCompleted = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "";


$errors = [];
$overlay = '';

if (isset($_GET['add'])){
  $page = template('templates/add.php', ['projects' => $projects]);
  $overlay = 'overlay';
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
  $task = $_POST;
  if (empty($task["name"])){
    $errors += ['name'=> 'Заполните поле!'];  
  }
  if (empty($task["project"])){
    $errors += ['project'=> 'Укажите проект!'];  
  }
  if(count($errors)){
    $page = template('templates/add.php', ['projects' => $projects, 'errors' => $errors]);
    $overlay = 'overlay';
  }else {
  if (empty($_POST["date"])) {
    $form_date = null;
  } else {
    $form_date = date("d.m.Y", strtotime($_POST["date"]));
  }
  if (isset($_FILES["preview"]["name"])) {
    $file_name = $_FILES["preview"]["name"];
    $file_path = 'uploads/';
    $file_url = 'uploads/' . $file_name;
    $uploaded_file = move_uploaded_file($_FILES["preview"]['tmp_name'], $file_path . $file_name);
  }
  
  array_unshift($task_table, [
    "task" => $_POST["name"],
    "date" => $form_date,
    "category" => $_POST["project"],
    "file_name" => $_FILES["preview"]["name"],
    "file_url" => $uploaded_file,
    "realization" => false
  ]);

  $page = template('templates/main.php',['showCompleted' => $showCompleted, 'show_complete_tasks' => $show_complete_tasks ,'task_table' => $task_table]);
  }
}

elseif(isset($_GET['id'])) {
  $task_category = [];
  $category_id = intval($_GET['id']);
  if (array_key_exists($category_id, $projects)) {
    foreach ($task_table as $val){
      if ($val['category']== $projects[$category_id]){
        array_push($task_category, $val);
      }
    }
    $page = template('templates/main.php',['showCompleted' => $showCompleted,'show_complete_tasks' => $show_complete_tasks ,'task_table' => $task_category]);
  }
  else {
    http_response_code(404);
    $page = "Категория не найдена";
  }
}
else {
  $page = template('templates/main.php',['showCompleted' => $showCompleted,'show_complete_tasks' => $show_complete_tasks ,'task_table' => $task_table]);
}

$layout = template('templates/layout.php',['overlay' => $overlay, 'content' => $page, 'title' => 'Дела в порядке','usre_name' => 'Константин', 'projects' => $projects, 'task_table' => $task_table]);
 
print($layout);
?>
