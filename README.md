# [Pmions](https://pmions.org): Simple PHP Database Migrations


## Intro

Pmions makes it ridiculously easy to manage the database migrations for your PHP app. In less than 5 minutes, you can install Pmions and create your first database migration. Pmions is just about migrations without all the bloat of a database ORM system or framework.

### Features

* Write database migrations using database agnostic PHP code.
* Migrate up and down.
* Migrate on deployment.
* Seed data after database creation.
* Get going in less than 5 minutes.
* Stop worrying about the state of your database.
* Take advantage of SCM features such as branching.
* Integrate with any app.

### Supported Adapters

Pmions natively supports the following database adapters:

* MySQL
* PostgreSQL
* SQLite
* Microsoft SQL Server

## Install & Run


### Composer

The fastest way to install Pmions is to add it to your project using Composer (https://getcomposer.org/).

1. Install Composer:

    ```
    curl -sS https://getcomposer.org/installer | php
    ```

1. Require Pmions as a dependency using Composer:

    ```
    php composer.phar require robmorgan/Pmions
    ```

### As a Phar

You can also use the Box application to build Pmions as a Phar archive (https://box-project.github.io/box2/).

1. Clone Pmions from GitHub

    ```
    git clone https://github.com/cakephp/Pmions.git
    cd Pmions
    ```

1. Install Composer

    ```
    curl -s https://getcomposer.org/installer | php
    ```

## Documentation


## Contributing

Please read the [CONTRIBUTING](CONTRIBUTING.md) document.

## News & Updates

Follow [@CakePHP](https://twitter.com/cakephp) on Twitter to stay up to date.

## Limitations

### PostgreSQL

- Not able to set a unique constraint on a table (<https://github.com/cakephp/Pmions/issues/1026>).


## Misc

### Version History

Please read the [release notes](https://github.com/sienekib20/pmions/releases).

### License

(The MIT license)

Copyright (c) 2024 Siene K. Kelyson

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
