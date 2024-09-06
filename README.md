# Blog Application

## Overview

This project is a Laravel-based blog application featuring a RESTful API and a user-friendly interface. It enables users to manage posts and comments with varying levels of access.

## The application includes two roles: Admin and User, and two models: Posts and Comments.

### Admins have the ability to:

Create, Update, and Delete Posts

### Users have the ability to:

Create, Update, and Delete Comments

### Both Admins and Users can:

Index and Show Posts and Comments



## Getting Started

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP (7.4 or higher)
- Composer
- Node.js and npm
- Laravel 
- mysql 

### Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Balqeesqasem/blog.git
   cd blog


## Set Up the Environment

Add .env file (I will share it)

## Install Dependencies

```bash
  // Install PHP Dependencies 

     composer install

  // Install Node.js Dependencies

     npm install

  // Run Database Migrations   
     
     php artisan migrate

  // Seed the Database   
     
     php artisan db:seed
```


## Running the Application

Start the Laravel development server:

```bash

   php artisan serve

```   

Your application should now be running at http://localhost:8000.



## API Documentation:


You can view the Postman documentation for this project [API Blogs Application](https://documenter.getpostman.com/view/11123143/2sAXjRUoyL).
