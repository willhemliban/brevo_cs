# Brevo case study

This is a simple symfony project that loads a csv into a psql database, scores the clients and categorizes them then allow to search through it with a simple interface.

## Prerequisite

- linux
- PHP 8.1 atleast
- psql installed

## Installing

1. Clone the project from this repository
```git clone https://github.com/username/repository-name.git```

2. Navigate to the directory

3. Install Dependencies
```composer install```

6. Set up the database
```php bin/console doctrine:database:create```
alternatively there is also a dump of the database in database/dump.sql

- run the command to transfer the data from the csv to the db
```php bin/console app:import-csv-data```

7. Start the server
```symfony server:start```

8. Access it on localhost:8000
