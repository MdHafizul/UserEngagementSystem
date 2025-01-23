# Naluri Project

A PHP-based system for tracking users engagement with task, and their analyses. This project includes database integration and features that allow role-based user management, task assignment, and tracking of task status.

---

## Table of Contents
1. [Features](#features)
2. [Prerequisites](#prerequisites)
3. [Setup and Installation](#setup-and-installation)
4. [Database Configuration](#database-configuration)
5. [Running the System](#running-the-system)
6. [License](#license)

---

## Features
- **User Roles**: Admin, Employee, and Patient roles with distinct permissions.
- **Task Management**: Create, assign, and track tasks with real-time status updates.
- **Task Analysis**: Generate reports and track task completion metrics.

---

## Prerequisites
Before setting up the project, ensure you have the following installed:
1. **XAMPP** or similar software (includes Apache, PHP, and MySQL).
2. A **web browser** to access the system.
3. A **text editor** or IDE for code modification (optional).
4. **Git** (optional, for version control).

---

## Setup and Installation

### 1. Clone the Repository
First, clone the project repository to your local machine:
```bash
git clone https://github.com/yourusername/naluri-project.git
```

### 2 .Move the Project Files
Place the project folder in the htdocs directory of your XAMPP installation:
```
C:\xampp\htdocs\naluri-project
```

### 3. Start Apache and MySQL
Open the XAMPP Control Panel and start the Apache and MySQL services.

## Database Configuration

### 1. Create the Database
Open phpMyAdmin (usually accessible at http://localhost/phpmyadmin).
Create a new database named naluridatabase.

### 2. Import the SQL Dump
In phpMyAdmin, select the naluridatabase database.
Click the Import tab.
Select the naluri-database.sql file located in the database folder of the project.
Click Go to execute the script and populate the database.

### 3. Verify Database Tables
Ensure the following tables are created:
```
users
tasks
taskanalysis
user_tasks
```

## Running the System

### 1. Access the System
Open your web browser.
Navigate to http://localhost/naluri-project.

### 2. Login
Use the default admin credentials:

Email: admin@gmail.com
Password: admin01

You can update these credentials in the users table of the database.

### 3. Explore the Features
```
Manage users, assign tasks, and view task analyses.
Test the functionality using different roles (Admin, Employee, Patient).
```

## Notes
Ensure the config.php file is correctly configured with your database credentials.

## License
This project is open-source and available under the MIT License.
