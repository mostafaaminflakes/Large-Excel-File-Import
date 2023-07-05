## Overview

Import extra large excel files with no repetitions in a very short time.

## System Requirements

-   PHP 8.1
-   Composer 2.5.\*
-   Laravel 10

## Features

-   Queues
-   Jobs
-   Commands
-   Database transactions
-   Chunking
-   Upserts
-   Error handling
-   Data validation
-   Logging

## Usage

-   Clone the repository.

    ```
    $ git clone https://github.com/mostafaaminflakes/Large-Excel-File-Import.git
    $ cd Large-Excel-File-Import
    $ composesr install
    ```

-   Create a database and populate the [.env] file with its credentials.

-   Migrate and serve.

    ```
    $ php artisan migrate
    $ php artisan serve
    ```

-   Queue.

    ```
    $ php artisan queue:listen --queue=csv
    ```

-   Run this command to start the import process [and watch the queue window].

    ```
    $ php artisan import:csv
    ```

If you wish to change the source CSV file, you can replace the file `products.csv` inside `/storage/csv/` with another file with the same name and structure but different data and then run the import command again.

## R&D concepts

While creating this project, the following ideas were R&D:

-   LOAD DATA INFILE.
-   PHP Generators.
-   Laravel LazyCollections.
-   The Process Component in Symfony to avoid timeout issues with Laravel jobs. This will allow multiple sub processes [synchronous or asynchronous].

## Notes

-   [Laravel Excel](https://github.com/SpartnerNL/Laravel-Excel) package is used for CSV file imports.
-   Failed chunked import logs can be found in `/storage/logs/imports.log`.
-   CSV import is tested on 2 machines. It took ~3 minutes to import ~130,000 records. With notifications and logging.
