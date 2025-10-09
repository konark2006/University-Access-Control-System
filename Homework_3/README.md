# Homework 3 — Query Set & MySQL Log

## Run steps on CLAMV
1. `ssh <your_login>@<clamv-hostname>`
2. `cd ~/University-Access-Control-System && git pull`
3. `mysql -u <mysql_user> -p`

In the MySQL prompt:
```sql
CREATE DATABASE IF NOT EXISTS `database_project`;
USE `database_project`;
SOURCE Homework_3/sql/schema.sql;
SOURCE Homework_3/sql/sample_data.sql;
tee Homework_3/logs/hw03_mysql.log;
SOURCE Homework_3/sql/queries/all_queries.sql;
notee;
exit;