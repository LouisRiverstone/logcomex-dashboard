CREATE DATABASE IF NOT EXISTS `laravel`;
GRANT ALL PRIVILEGES ON `laravel`.* TO 'root'@'%';
FLUSH PRIVILEGES; 

CREATE DATABASE IF NOT EXISTS `laravel_testing`;
GRANT ALL PRIVILEGES ON `laravel_testing`.* TO 'root'@'%';
FLUSH PRIVILEGES; 