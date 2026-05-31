# CLI/HTTP Framework Assignments

A lightweight CommonF framework built in plain PHP for completing back-end software development assignments.

## About

This repository contains a collection of task assignments, where each internal application represents a different development task. The framework is designed to provide a simple, reusable foundation for CLI-based applications while maintaining clean, extensible architecture.

&nbsp;

## Project Structure
```
Apps/
  ├──AdvertisingBidAuction/
  │  ├── public/
  │  └────── index.php       # entry point
  ├──EscapeALabyrinth/
  │  ├── public/
  │  └────── index.php       # entry point
  └──WordFrequencyCounter/
     ├── public/
     ├────── migrations.php  # migrations entry point
     └────── index.php       # http entry point
```

&nbsp;

## Requirements

- **PHP Version**: 7.4 or higher
- **Composer**: For testing frameworks

## Framework Overview

CommonF is a lightweight PHP framework designed for rapid development and task completion. It emphasizes:

- **Simplicity**: Pure PHP implementation without heavy dependencies
- **Extensibility**: Easy-to-extend core components
- **Modularity**: Each application is self-contained with its own configuration
- **Decoupled Architecture**: Every module is completely decoupled for easy reuse and testing

### Design Patterns

The framework leverages industry-standard design patterns to achieve decoupling and maintainability:

- **Front Controller**: Coordinates request handling and routing for different application types (CLI, HTTP)
- **Adapter Pattern**: Adapts interfaces between components and external systems (e.g., IDataStreamAdapter, ILoggerAdapter)
- **Container Pattern**: Manages dependency injection, service lifecycle, and configuration loading. Stores and instantiates services on demand through the `resolve()` methods
- **Repository Pattern**: Abstracts data access and persistence logic (FileRepository, SQLiteRepository)
- **Resolver Pattern**: Resolves dependencies and service instantiation through `resolve()`, `resolveNested()`, and `resolveAllValidators()` methods
- **Template Method Pattern**: Defines algorithm structure in base classes (CommandAbstract, ControllerAbstract, FileStreamAbstract) for subclasses to implement
- **Dependency Injection Pattern**: Injects dependencies through constructors and fluent attachment methods (`attachApp()`, `attachValidators()`)

*****These patterns work together to ensure components remain loosely coupled while maintaining clear separation of concerns. Decoupled design ensures minimal dependencies and easy testing***

&nbsp;

## Extending Core Components

To extend the main components of the framework for a new application:

### 1. **Creating a New Application**
   - Create a new directory under `/Apps` following the naming convention
   - Each application should maintain the same structural pattern as existing applications
   - Leverage decoupled modules from CommonF for maximum reusability

### 2. **Extending Base Classes**
   - Identify the core component you need to extend (e.g., Command, Service, Handler)
   - Inherit from the base class and override methods as needed
   - Place your extended class in your application's namespace

### 3. **Configuration**
   - Create an `app.config.php` or similar configuration file for your application
   - Define application-specific settings, constants, and dependencies
   - Utilize the Container for dependency management

&nbsp;

## Running Applications

Each application in this repository is self-contained. To run any specific application:

1. Navigate to the application's directory:
   ```bash
   cd Apps/{ApplicationName}
   ```

2. Read the application-specific `README.md` file located in that directory for detailed instructions on how to run and configure the application.

&nbsp;

## Applications

Below are the applications included in this assignment repository:

- **[AdvertisingBidAuction](/Apps/AdvertisingBidAuction)** - Advertising Bid Auction application
- **[EscapeALabyrinth](/Apps/EscapeALabyrinth)** - Escape A Labyrinth application
- **[WordFrequencyCounter](/Apps/WordFrequencyCounter)** - Word Frequency Counter application

*Each application directory contains its own README.md with specific instructions.*

## Getting Started

1. Choose an application from the list above
2. Navigate to that application's directory under `/Apps`
3. Read the application's README.md for specific setup and execution instructions

---

For more information about individual applications and their specific requirements, please refer to the README file in each application directory.

&nbsp;

## Integration Testing is loading..
