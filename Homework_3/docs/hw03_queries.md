# HW03 Queries (UACS)

Each teammate contributes **3 queries** with a short natural-language description + the SQL.  
Below is a consolidated set of 12 queries (suitable for a team of 4). Adjust per teammate if needed.

> **How to run on CLAMV**
> 1) `mysql -u <mysql_user> -p`  
> 2) `USE database_project;`  
> 3) `SOURCE Homework_3/sql/schema.sql;` then `SOURCE Homework_3/sql/sample_data.sql;`  
> 4) Start logging: `tee Homework_3/logs/hw03_mysql.log;`  
> 5) `SOURCE Homework_3/sql/queries/all_queries.sql;`  
> 6) Stop logging: `notee;`

All SQL is in `Homework_3/sql/queries/all_queries.sql`. Queries cover multiple joins, aggregation, and GROUP BY/HAVING.