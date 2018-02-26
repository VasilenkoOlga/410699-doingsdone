CREATE DATABASE doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


USE doingsdone;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(64),
    user_id INT
);

CREATE TABLE task_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_add DATETIME(0),
    date_done DATETIME(0),
    task CHAR(255),
    file_path CHAR(255),
    date_deadline DATETIME(0),
    user_id INT,
    category INT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_reg DATETIME(0),
    email CHAR(128),
    name CHAR(64),
    password CHAR(64),
    contact_info CHAR(255)
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX file_path ON task_table(file_path);
CREATE UNIQUE INDEX projects ON projects(name);
CREATE INDEX task_name ON task_table(task);
