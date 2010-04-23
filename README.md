# Tests for Jelly

These tests are designed to be run with Kohana 3.0's [Unit Test](http://github.com/kohana/unittest) module. They require access to a MySQL, PostgresSQL, or SQLite database. Preparing the database is handled by the test suite.

## How to choose the database you'd like the tests to run on

1. **Update the database configuration** in `config/database.php`. Jelly's test models use the database group named `jelly`, so you can configure that group to connect to your MySQL or SQLite database (an SQLite in-memory database is configured by default).

2. **Update the dump file location** in `config/jelly-tests.php`. A `.php` file will be loaded from the `data` folder with the name of the value you set this to. Currently, dump files are provided for MySQL and SQLite. As such, currently only `sqlite` and `mysql` are valid values for this configuration option.

3. **If you're testing with Postgres** you'll need to install the [PostgresSQL database driver module](http://github.com/cbandy/kohana-postgresql) in your bootstrap.

