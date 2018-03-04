<?php
require_once('function.php');
require_once('mysql_helper.php');
require_once('init.php');

// показывать или нет выполненные задачи
$show_complete_tasks = 1;

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
$user = NULL;
$login_errors = [];
$errors_regist = [];
$project_errors = [];

session_start();

$projects = [];
$task_table = [];

if (isset($_SESSION["user"])) {
  
  if (isset($_GET['complete_task_id'])) {
    $taskId = $_GET['complete_task_id'];
    
    $query = "SELECT * FROM task_table WHERE `id` = '" . $taskId . "'";
    $result = mysqli_query($link, $query);
    $task = mysqli_fetch_assoc($result);
    
    $taskDateDone = $task['date_done'];
    
    if ($taskDateDone !== NULL) {
      $query = "UPDATE task_table SET date_done = NULL WHERE id = '" . $taskId . "'";
    } else {
      $query = "UPDATE task_table SET date_done = '".date('Y-m-d H:i:s')."' WHERE id = '" . $taskId . "'";
    }
    $result = mysqli_query($link, $query);
  }
  
  if (intval($_SESSION['user']['id']) > 0) {
    $projects = getUserProjects($link, $_SESSION['user']['id']);
    $task_table = getUserTasks($link, $_SESSION['user']['id']);
  }

  if (isset($_GET['modal-project'])) {
  
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add_project"])) {
      if (empty($_POST["name"])) {
        $project_errors["name"] = "Заполните это поле";
      }
      
      if (count($project_errors)) {
          $modal = template("templates/modal-project.php", ['project_errors' => $project_errors]);
      } else {
        $sql = 'INSERT INTO projects (name, user_id) VALUES(?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$_POST['name'], $_SESSION['user']['id']]);
        mysqli_stmt_execute($stmt);
      }
    } else {
      $modal = template("templates/modal-project.php", []);
    }
  }

  
  if (isset($_GET['add'])) {
    $page = template('templates/add.php', ['projects' => $projects]);
    $overlay = 'overlay';
  } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["project"])) {
    $task = $_POST;
    if (empty($task["name"])){
      $errors += ['name'=> 'Заполните поле!'];  
    }
    
    if (empty($task["project"])){
      $errors += ['project'=> 'Укажите проект!'];  
    }
    
    if (time() > strtotime($_POST["date_deadline"])){
      $errors += ['date_deadline'=> 'Укажите верную дату!'];  
    }
    
    if (count($errors)) {
      $page = template('templates/add.php', ['projects' => $projects, 'errors' => $errors]);
      $overlay = 'overlay';
    } else {
      if (empty($_POST["date_deadline"])) {
        $form_date = null;
      } else {
        
        $form_date = date("Y-m-d H:i:s", strtotime($_POST["date_deadline"]));
      }
      
      if (isset($_FILES["preview"]["name"])) {
        $file_name = $_FILES["preview"]["name"];
        $file_path = 'uploads/';
        $file_url = 'uploads/' . $file_name;
        $uploaded_file = move_uploaded_file($_FILES["preview"]['tmp_name'], $file_path . $file_name);
      }
      
      $sql = 'INSERT INTO task_table (date_add, task, date_deadline, user_id, category) VALUES(NOW(), ?, ?, ?, ?)';
      $stmt = db_get_prepare_stmt($link, $sql, [
        $task['name'],
        $form_date,
        $_SESSION['user']['id'],
        $task['project']
      ]);
      mysqli_stmt_execute($stmt);
      
      header("Location:/index.php");
    }
  } elseif(isset($_GET['id'])) {
    $task_category = [];
    $category_id = intval($_GET['id']);
    
    if (array_key_exists($category_id, $projects)) {
      foreach ($task_table as $task) {
        if ($task['category'] == $projects[$category_id]['id']) {
          $task_category[] = $task;
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
  $user = $_SESSION['user'];
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
        
        if ($user = searchUserByEmail($link, $login_post["email"])) {
          if (password_verify($login_post["password"], $user["password"])) {
            $_SESSION["user"] = $user;
            if (intval($user['id']) > 0) {
              $projects = getUserProjects($link, $user['id']);
              $task_table = getUserTasks($link, $_SESSION['user']['id']);
            }
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
if (isset($_GET['register'])) {
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["register"])) {
    $register_form = $_POST;
    
    if (empty($register_form["email"])){
      $errors_regist['email'] = 'Введите e-mail!';
    }
    
    if (empty($register_form["password"])){
      $errors_regist['password'] = 'Введите пароль';  
    }
    
    if (empty($register_form["name"])){
      $errors_regist['name'] = 'Введите имя';  
    }
    
    if (!empty($register_form["email"]) && !filter_var($register_form["email"], FILTER_VALIDATE_EMAIL)) {
        $errors_regist['email'] = 'E-mail введён некорректно';
    }
    else {
      $query = "SELECT * FROM users WHERE `email` = '" . $register_form["email"] . "'";
      $result = mysqli_query($link, $query);
      if (mysqli_num_rows($result) !== 0) {
        $errors_regist['email'] = 'Такой пользователь уже существует';
      }
    }
    
    if (isset($errors_regist["email"]) || isset($errors_regist["password"]) || isset($errors_regist["name"])) {
      $page = template("templates/register.php", ["errors_regist" => $errors_regist]);
    }
    else {
        $password = password_hash($register_form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (email, password, name, date_reg) VALUES(?, ?, ?, NOW())';
        $stmt = db_get_prepare_stmt($link, $sql, [$register_form['email'], $password, $register_form['name']]);
        mysqli_stmt_execute($stmt);
        header('Location: index.php?login');
    }
  } else {
    $page = template('templates/register.php', []);
  }
}

if (isset($_GET['logout'])) {
    session_unset();
    $page = template('guest.php', []);
    header("Location:/index.php");
}

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