<? 

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'doingsdone'
];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

if (!$link)
{
    print('Ошибка подключения: ' . mysqli_connect_error()); 
};

?>