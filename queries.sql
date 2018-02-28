
USE doingsdone;

INSERT INTO users SET email = 'ignat.v@gmail.com', name = 'Игнат', password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka';
INSERT INTO users SET email = 'kitty_93@li.ru', name = 'Леночка', password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa';
INSERT INTO users SET email = 'warrior07@mail.ru', name = 'Руслан', password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW';

INSERT INTO projects SET name = 'Входящие', user_id = 1;
INSERT INTO projects SET name = 'Учеба', user_id = 1;
INSERT INTO projects SET name = 'Работа', user_id = 1;
INSERT INTO projects SET name = 'Домашние дела', user_id = 1;
INSERT INTO projects SET name = 'Авто', user_id = 1;

INSERT INTO task_table SET task = 'Собеседование в IT компании', user_id = 1, category = 3,  date_deadline = '2018-02-11T01:30:00';
INSERT INTO task_table SET task = 'Выполнить тестовое задание', user_id = 1, category = 3,  date_deadline = '2018-05-25T01:30:00';
INSERT INTO task_table SET task = 'Сделать задание первого раздела', user_id = 1, category = 2,  date_deadline = '2018-02-21T01:30:00';
INSERT INTO task_table SET task = 'Встреча с другом', user_id = 1, category = 1,  date_deadline = '2018-04-22T01:30:00';
INSERT INTO task_table SET task = 'Купить корм для кота', user_id = 1, category = 4,  date_deadline = '2018-02-28T01:30:00';
INSERT INTO task_table SET task = 'Заказать пиццу', user_id = 1, category = 4,  date_deadline = '2018-02-11T01:30:00';

/* получить список из всех проектов для одного пользователя */
SELECT * FROM projects WHERE user_id = 1;

/* получить список из всех задач для одного проекта */
SELECT * FROM task_table WHERE category =2;

/* пометить задачу как выполненную */
UPDATE task_table SET date_done = '2018-02-21T03:30:00'  WHERE task = 'Сделать задание первого раздела';

/* получить все задачи для завтрашнего дня */
SELECT * FROM task_table WHERE DATE(date_deadline) = DATE_ADD(CURDATE(), INTERVAL 1 DAY);

/* обновить название задачи по её идентификатору */
UPDATE task_table SET task = 'Поиграть с котом' WHERE id = 4;