<?php
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
?>