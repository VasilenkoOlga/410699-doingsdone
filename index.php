<?php
require_once('function.php');
require_once('userdata.php');

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
  header("Location: " . $_SERVER["HTTP_REFERER"]);
}

$showCompleted = (isset($_COOKIE["showcompl"])) ? $_COOKIE["showcompl"] : "";

$errors = [];
$overlay = '';
$modal = NULL;
$page = NULL;
$login_errors = [];

session_start();
if (isset($_SESSION["user"])) {

  if (isset($_GET['add'])) {
    $page = template('templates/add.php', ['projects' => $projects]);
    $overlay = 'overlay';
  } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["project"]) ) {
    $task = $_POST;
    if (empty($task["name"])){
      $errors += ['name'=> 'Заполните поле!'];  
    }
    
    if (empty($task["project"])){
      $errors += ['project'=> 'Укажите проект!'];  
    }
    
    if (count($errors)) {
      $page = template('templates/add.php', ['projects' => $projects, 'errors' => $errors]);
      $overlay = 'overlay';
    } else {
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
  } elseif(isset($_GET['id'])) {
    $task_category = [];
    $category_id = intval($_GET['id']);
    
    if (array_key_exists($category_id, $projects)) {
      foreach ($task_table as $val) {
        if ($val['category'] == $projects[$category_id]) {
          $task_category[] = $val;
        }
      }
 
      $page = template('templates/main.php', ['showCompleted' => $showCompleted, 'show_complete_tasks' => $show_complete_tasks, 'task_table' => $task_category]);
    } else {
      http_response_code(404);
      $page = "Категория не найдена";
    }
  } else {
    $page = template('templates/main.php',['showCompleted' => $showCompleted,'show_complete_tasks' => $show_complete_tasks ,'task_table' => $task_table]);
  }

} else { 
    if (isset($_GET["login"])) {
     $page = template('templates/guest.php', []); 
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["login"])) {
        $login_post = $_POST;
        
        if (empty($login_post["email"])) {
          $login_errors["email"] = "Заполните это поле";
        }
        
        if (empty($login_post["password"])) {
          $login_errors["password"] = "Введите пароль!"; 
        }
        
        if (!filter_var($login_post["email"],FILTER_VALIDATE_EMAIL)) {
          $login_errors["email"] = 'Неверный e-mail';  
        }
        
        if($user = searchUserByEmail($login_post["email"], $users)) {
          if (password_verify($login_post["password"], $user["password"])) {
            $_SESSION["user"] = $user;
          } else {
            $login_errors["password"] = "Введите пароль!";
          }                           
        } else {
          $login_errors["email"] = "Неверный e-mail"; 
        }
        
        if (count($login_errors)) {
          $modal = template("templates/modal-authorization.php", ["login_post" => $login_post, "login_errors" => $login_errors]);
        } else {
          $page = template('templates/main.php', ['showCompleted' => $showCompleted, 'show_complete_tasks' => $show_complete_tasks, 'task_table' => $task_table]);
        }
      } else {
        $modal = template('templates/modal-authorization.php', []);
      }

    } else { 
      $page = template('templates/guest.php', []);
    }
}

if (isset($_GET['logout'])) {
    session_unset();
    $page = template('guest.php', []);
    header("Location:/index.php");
}

$user = $_SESSION['user']['name'];

$layout = template('templates/layout.php',[
  'overlay' => $overlay, 
  'modal' => $modal, 
  'content' => $page, 
  'title' => 'Дела в порядке', 
  'projects' => $projects, 
  'user' => $user, 
  'task_table' => $task_table]);
 
print($layout);
?>