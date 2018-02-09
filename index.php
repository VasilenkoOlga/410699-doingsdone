<?php
require_once('function.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

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
		"date" => "01.06.2018",
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
		"realization" => false
    ],
	[ 
		"task" => "Купить корм для кота",
		"date" => "Нет",
		"category" => "$projects[4]",
		"realization" => false
    ],
	[ 
		"task" => "Заказать пиццу",
		"date" => "Нет",
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

$page = template('templates/main.php',['show_complete_tasks' => $show_complete_tasks ,'task_table' => $task_table]);

$layout = template('templates/layout.php',['content' => $page, 'title' => 'Дела в порядке','usre_name' => 'Константин', 'projects' => $projects, 'task_table' => $task_table]);
 
print($layout);
?>
