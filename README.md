# Task Management System

## Introduction

This a system that can be used to create, assign, work on and follow up on tasks in a firm. The system works in a department organised environment.

<!-- ## Table of Contents -->

## Hosted version

Currently the system is hosted at: <http://task_management.cf>

### Available users to sign in to the system

1. Sales Department Manager

    Email: eondiek@cytonn.com Password: secret

2. Sales Department Member

    Email: gmulonga@cytonn.com Password: secret

3. IT Department Manager

    Email: akamanu@cytonn.com Password: secret

4. IT Department Member

    Email: jmatiku@cytonn.com Password: secret

## Setting up

### Prerequisites

- git and composer installed
- laravel basic knowledge
- a mailtrap account
- optionally have homestead (linux, windows) or valet(mac) installed

### Setting up on local machine

1. Navigate to a directory of your choice, for instance

        cd /var/www

2. Clone the repository

        git clone https://johnmusiu@bitbucket.org/johnmusiu/project-management-coding-challenge.git

3. cd into the project folder

        cd project-management-coding-challenge/

4. Run

        composer install

5. Setup .env file

        cp .env.example .env

6. Generate app key

        php artisan key:gen

7. Setup database in your app.

    If you are on Homestead you only need specify your database name on .env file and update your Homestead.yaml file with the new site configurations.

    Otherwise, setup your local sql server and create the database then update the database username and password on the .env file.

8. Run migrations and seeders

        php artisan migrate --seed

9. Configure mailtrap on your app

10. If not on homestead run

        php artisan serve

    Now visit <http://127.0.0.1:8000> on your browser

    If on homestead, visit <http://the.url.you.set.on.yaml.file.domain>

### Server Deployment (Ubuntu 16.04)

Refer to this tutorial to get up and running quickly:

[Deploy Laravel on Ubuntu 16.04 and  Nginx](https://www.digitalocean.com/community/tutorials/how-to-deploy-a-laravel-application-with-nginx-on-ubuntu-16-04)

### Accounts you can use out of the box

These are similar to ones hosted on the live server

## Usage

You can:

1. Add task categories

2. Add tasks

3. Assign user to a task

4. Update task progress for tasks assigned to you

5. Complete tasks

6. Edit tasks you have permissions to

## Licenses

This project is licensed under the MIT License - see the [license](LICENSE)

## Author

John Musiu