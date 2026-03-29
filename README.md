# Meditation Tracker - Laravel Assessment

A full-stack Laravel web application that allows users to securely track their daily meditation sessions view their history and monitor their total meditation time.

## Core Features Implemented
1. **User Authentication:** Secure Registration, Login, and Logout functionality (using hashed passwords and CSRF protection).
2. **Interactive Dashboard:** Displays the total number of meditation sessions and total minutes meditated dynamically.
3. **Session Logging:** A clean UI form to manually log a completed meditation session (Date, Duration, Optional Notes).
4. **Session History:** A tabular view of all past meditation sessions, ordered by the most recent first.

## Bonus Feature: Secure RESTful API (Sanctum)
I have implemented a stateless RESTful API endpoint returning the user's meditation history in JSON format. To demonstrate industry standards, I used **Laravel Sanctum** for token-based authentication.

**How to Test the API in Postman:**
1. Login to the application via the web browser.
2. Visit `http://localhost:8000/get-my-token` in your browser to instantly generate your personal `Bearer Token`.
3. Open Postman and make a **GET** request to `http://localhost:8000/api/sessions`.
4. In the **Authorization** tab of Postman, select **Bearer Token**, paste your token, and hit Send!

## Tech Stack
* **Backend:** Laravel 11, PHP 8.x
* **Frontend:** Blade Templating, Bootstrap 5, FontAwesome, JavaScript/AJAX
* **Database:** SQLite / MySQL

## Setup Instructions
To run this project locally, follow these steps:

1. Clone the repository
2. Run `composer install` to install dependencies.
3. Copy `.env.example` to `.env` and configure your database settings.
4. Run `php artisan key:generate`
5. Run `php artisan migrate` to create database tables.
6. Run `php artisan serve` and visit `http://localhost:8000`