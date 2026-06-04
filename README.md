# Task Manager - Docker Deployment

Това е уеб приложение за управление на задачи (Task Manager), разработено на PHP и база данни MySQL. Проектът е напълно контейнеризиран с Docker, което позволява изолирано и лесно стартиране без нужда от ръчна инсталация на среди като XAMPP/LAMP.

---

## Docker Hub Линкове
* **Уеб приложение (PHP/Apache):** [Линк към Docker Hub профила](https://hub.docker.com/)
* **База данни:** Използва официален сертифициран образ на mysql:8.0

---

## Архитектура на контейнерите

Проектът е разделен на два независими контейнера, които комуникират помежду си в споделена мрежа:
1. taskmanager_web: PHP 8.2 + Apache сървър, работещ на порт 8080.
2. taskmanager_db_container: MySQL 8.0 база данни, работеща на стандартния порт 3306, със заделен обем за персистентно запазване на данните (Volume persistent storage).

---

## Инструкции за локално стартиране

Следвайте тези стъпки, за да пуснете проекта на произволен компютър с инсталиран Docker:

### 1. Подготовка на средата
Уверете се, че локални услуги като XAMPP (Apache и MySQL) са спрени, за да няма конфликти с портовете на Docker контейнерите.

### 2. Стартиране на базата данни (MySQL)
Изпълнете следната команда в терминала, за да вдигнете контейнера с базата данни и да импортирате първоначалната структура:

sudo docker run -d --name taskmanager_db_container -p 3306:3306 -v db_data:/var/lib/mysql -v "$(pwd)/init.sql:/docker-entrypoint-initdb.d/init.sql" -e MYSQL_DATABASE=task_manager_db -e MYSQL_ALLOW_EMPTY_PASSWORD=yes mysql:8.0

### 3. Стартиране на уеб сървъра (PHP/Apache)
След като базата данни е готова, стартирайте уеб приложението и го свържете към нея:

sudo docker run -d --name taskmanager_web -p 8080:80 --link taskmanager_db_container:db -e DB_HOST=db taskmanager_web:latest

### 4. Достъп до приложението
Отворете любимия си браузър и заредете следния адрес:
http://localhost:8080

---

## Спиране и управление на контейнерите

* За проверка на работещите контейнери:
sudo docker ps

* За спиране и премахване на контейнерите:
sudo docker rm -f taskmanager_web taskmanager_db_container
