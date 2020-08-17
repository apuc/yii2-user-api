**Для установки приложения необходимо:**
1) Сконфигурировать параметры БД в файле common\config\main-local.php
2) Выполнить команду php yii migrate/up в корне проекта

**Реализованный функционал:**
1) Создание таблиц через миграции и сиды
2) Регистрация и авторизация пользователя (через интерфейс и RestApi)
2) Заполнение заявки после регистрации пользователя

**Использование RESTful API:**
Для пользованием api необходима аутентификация типа "Basic", передавая в запросе username (в нашем случае email) и password.
Api поддерживает широкоиспользуемые типы запросов:
- GET /user: получение постранично списка всех пользователей;
- HEAD /user: получение метаданных листинга пользователей;
- POST /user: создание нового пользователя;
- GET /user/123: получение информации по конкретному пользователю с id равным 123;
- HEAD /user/123: получение метаданных по конкретному пользователю с id равным 123;
- PATCH /user/123 и PUT /user/123: изменение информации по пользователю с id равным 123;
- DELETE /user/123: удаление пользователя с id равным 123;
- OPTIONS /user: получение поддерживаемых методов, по которым можно обратится к /user;
- OPTIONS /user/123: получение поддерживаемых методов, по которым можно обратится к /user/123.
