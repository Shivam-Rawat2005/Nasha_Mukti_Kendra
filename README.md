Nasha Mukti Kendra – De-Addiction Management System

A comprehensive web-based platform for managing rehabilitation centers and beneficiaries across India.

Live Demo:

https://nashamukti.ct.ws/

Overview

Nasha Mukti Kendra (De-Addiction Management System) is a centralized web application designed to streamline the operations of rehabilitation centers and track beneficiary progress. It enables efficient center management, beneficiary monitoring, statistical reporting, and data-driven insights to support India’s Nasha Mukti mission.

Team

Developed by:

Aniket

Satyam Kumar

Shivam Rawat

Arnav Singh

Features
📊 Dashboard

Real-time statistics with interactive visualizations:

Total registered centers

Active beneficiaries count

Success rate insights

State-wise center distribution

Addiction-type distribution

Monthly admissions trend

Built-in Dark Mode

🏥 Center Management

Add new rehabilitation centers

Track capacity vs. occupancy

Manage contact details, location & center metadata

👤 Beneficiary Management

Register and track beneficiaries

Monitor admission dates and recovery progress

Record interventions, outcomes & activity logs

📚 Records & Analytics

Filterable and searchable beneficiary records

Rich statistical analysis

Interactive Chart.js visualizations

Advanced filters (state, addiction type, recovery status)

🔐 User Authentication

Secure login system

Role-based access (Admin & Client)

Session-based authentication and protected routes

Project Structure
nashamukti/
├── assets/
│   └── images/
├── config/
│   ├── db.php
│   └── auth.php
├── includes/
│   └── header.php
├── index.php
├── about.php
├── records.php
├── statistics.php
├── add_center.php
├── add_beneficiary.php
├── login.php
├── register.php
├── logout.php
├── setup_db.php
├── update_stats.php
└── README.md

Technology Stack
Frontend

HTML5

Tailwind CSS (CDN)

JavaScript

Chart.js

Font Awesome

AOS (Animate On Scroll)

Backend

PHP

MySQL / MariaDB

Session-based authentication

Prerequisites

PHP 7.4+

MySQL 5.7+

Apache/Nginx

Modern browser

Installation Guide
1. Web Server Setup

Use XAMPP/WAMP/MAMP.
Place project folder in your server root:

C:\xampp\htdocs\nashamukti\

2. Database Setup

Create database: nasha_mukti1_db

Visit setup_db.php in your browser

Tables + sample data will be generated automatically

3. Configuration

Edit DB credentials in:

config/db.php


Default settings:

Host: localhost

User: root

Password: (empty)

Database: nasha_mukti1_db

Database Schema
centers

id (PK)

name

address, state, city

contact_person, phone, email

capacity

created_at

beneficiaries

id (PK)

center_id (FK)

name, age, gender

address, phone

addiction_type

admission_date

status

created_at

interventions

id (PK)

beneficiary_id (FK)

intervention_type

description

date

outcome

created_at

addiction_types

id (PK)

name

count

monthly_admissions

id (PK)

month, year

count

created_at

users

id (PK)

username, password

email

role (admin/client)

created_at

Usage
1. Initial Setup

Visit:

/setup_db.php


Creates tables + inserts initial sample data.

2. Login Credentials

Default Admin

Username: admin

Password: admin123

Users can create their own accounts as well.

3. Add Centers

(Admin only)
Fill out center details via Add Center page.

4. Manage Beneficiaries

Add new beneficiaries

Assign them to centers

Track progress & interventions

5. View Records

Access complete beneficiary list

Search and filter

Visit Statistics for charts & insights

6. UI / Theme

Includes dark mode toggle with saved preferences via local storage.

Deployment

The project is currently live at:
http://nashamukti.ct.ws/

To deploy on your own server:

Upload project files

Create database and update db.php

Run setup_db.php

Access the domain via browser

Maintenance

Perform periodic database backups

Ensure update_stats.php runs properly

Keep PHP & MySQL updated to stable versions

Support

For help, contact the development team or open an issue in the repository.

License

Released under the MIT License.
