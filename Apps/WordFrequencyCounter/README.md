# Word Frequency Counter

## Overview

This application is a Word Frequency Counter system that allows users to feed the system with text data and perform analysis on the processed information. The application is designed for independent operation of two main workflows: data ingestion (feeding) and data analysis/retrieval (querying). This enables multiple users to work with the system simultaneously - one feeding new text while others analyze the existing data.

## How It Works

The application:
1. Accepts text input through the web interface
2. Processes and analyzes word frequencies
3. Stores results in a SQLite database
4. Provides both CLI and HTTP interfaces for data management and querying
5. Supports word listing with pagination
6. Maintains independent data ingestion and analysis workflows

The system uses a SQLite database to persist word frequency data, making it suitable for both small-scale and moderately large text analysis tasks.

&nbsp;

## Requirements

- PHP 7.4 or higher
- SQLite support in PHP
- Web server or PHP built-in server for HTTP interface

&nbsp;

## Running the Application

Navigate to the public directory:
```
cd Apps/EscapeALabyrinth/public
```

```
php migrations.php migrate
php -S localhost:8000 index.php
```
&nbsp;

## Project Structure
```
root/
  └ Apps/
      └ WordFrequencyCounter/
        ├── public/
        ├────── index.php                 # HTTP entry point
        ├────── migrations.php            # CLI entry point
        └── core/
            ├── config/
            │   ├── app.php               # Application settings
            │   ├── commands.php          # CLI commands
            │   ├── validations.php       # Validation rules
            │   ├── router.php            # HTTP routes
            ├── controllers/
            │   ├── WordFrequencyController.php
            └── templates/
                ├── [other templates]
                └── notFound404.php
└── db_files/
    └── database.db               # SQLite database (created via migrations)
```

&nbsp;

## Database Setup

### Running Migrations

Before using the application, you must set up the database using migrations.

Create the database and tables:
- Creates the db_files directory in the root of the repository
- Creates the SQLite database
- Initializes all required tables for word frequency storage

```
php migrations.php migrate
```

Remove the database:
```
php migrations.php remove
```
This command:
- Deletes the SQLite database
- Removes the db_files directory
- Useful for resetting the application state

Available commands are configured in core/config/commands.php.

&nbsp;

## HTTP Interface

Start the PHP built-in web server from the public directory:


The HTTP server will run on localhost:8000 and all requests will be routed through index.php to the appropriate controllers and views.

## HTTP Routes

Routes are defined in core/config/router.php and map URLs to controller actions.

### Error Handling

GET **/otherwise**
- Custom 404 Not Found page
- Displayed when accessing non-existent URLs or resources

## Configuration

### Application Settings

Modify application-specific settings in core/config/app.php:
- Production mode
- Database file path (db_files directory)
- Database filename
- Pagination settings
- Template paths
- Application-specific constants

### HTTP Routes

Configure all URL routes in core/config/router.php:
- Route definitions with HTTP methods
- Controller and action mappings
- Route options and names

### Commands

Add or modify available CLI commands in core/config/commands.php:
- Command class mappings
- Command dependencies
- Command-specific configurations

### Validations

Control validation rules in core/config/validations.php:
- Text input validation
- Word validation rules
- Database operation validation
- Custom validator chains

## Features

### Independent Workflows

The architecture ensures:
- One user can feed data while another analyzes existing data
- No blocking between read and write operations
- SQLite database handles concurrent access appropriately
- Session management keeps user workflows isolated

## Templates

HTML templates are located in core/views/ and organized by feature:

core/otherwise/404.php
- Custom error page for not found routes
- User-friendly error message
- Navigation links to main pages
