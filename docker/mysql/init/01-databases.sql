CREATE DATABASE IF NOT EXISTS `bot`;

CREATE USER 'guest'@'%' IDENTIFIED BY 'guest';
GRANT ALL PRIVILEGES ON *.* TO 'guest'@'%';