# PHP post-comments processing

[English version](#english-version) | [Русская версия](#русская-версия)

---

## English Version {#english-version}

PHP project to store posts/comments in Oracle DB and search posts by comment text.

### Files
- `create_db.sql` – create tables
- `import_data.php` – load JSON data
- `search.php` – search interface (requires PHP server)

### How to Run
1. Create tables:  
   `sqlplus user/pass@localhost/XEPDB1 @create_db.sql`
2. Import data:  
   `php import_data.php`
3. Start PHP server and open browser:  
   `php -S localhost:8000`  
   then open `http://localhost:8000/search.php` and search (≥3 chars, case-insensitive)

---

## Русская версия {#русская-версия}

Простой проект на PHP для хранения постов и комментариев в Oracle DB и поиска постов по тексту комментариев.

### Файлы
- `create_db.sql` – создание таблиц
- `import_data.php` – загрузка данных из JSON
- `search.php` – интерфейс поиска (требуется PHP сервер)

### Как запустить
1. Создать таблицы:  
   `sqlplus user/pass@localhost/XEPDB1 @create_db.sql`
2. Загрузить данные:  
   `php import_data.php`
3. Запустить встроенный PHP сервер и открыть браузер:  
   `php -S localhost:8000`  
   затем открыть `http://localhost:8000/search.php` и искать (≥3 символов, без учёта регистра)
