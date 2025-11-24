Nasha Mukti Kendra – De-Addiction Management System

A comprehensive web-based platform for managing rehabilitation centers and beneficiaries across India.

Live Demo:

https://nashamukti.ct.ws/

Overview

Nasha Mukti Kendra is a centralized web application designed to streamline rehabilitation center operations, track beneficiaries, and generate statistical insights to support India’s de-addiction initiative.

Team

Developed by:

Aniket

Satyam Kumar

Shivam Rawat

Arnav Singh

Features
📊 Dashboard

Real-time statistics with:

Total centers count

Active beneficiaries

Success rate tracking

State-wise distribution

Addiction type distribution

Monthly admissions trends

Dark mode support

🏥 Center Management

Add new rehabilitation centers

Track capacity & occupancy

Manage contact, location & details

👤 Beneficiary Management

Register beneficiaries

Track admission & recovery status

Record interventions and outcomes

📚 Records & Analytics

Searchable & filterable records

Detailed statistical analysis

Interactive Chart.js visualizations

Advanced filtering by state, addiction type, status

🔐 User Authentication

Role-based access control (Admin/Client)

Secure login & registration

Session management

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

Tailwind CSS

JavaScript

Chart.js

Font Awesome

AOS Animations

Backend

PHP

MySQL / MariaDB

Session-based Authentication

Prerequisites

PHP 7.4+

MySQL 5.7+

Apache/Nginx

Modern browser

Installation
1. Web Server Setup

Place project in your server root:

C:\xampp\htdocs\nashamukti\

2. Database Setup

Create DB: nasha_mukti1_db

Run: setup_db.php

Tables + sample data auto-generated

3. Configuration

Edit DB config in:

config/db.php


Default:

Host: localhost

User: root

Password: (empty)

Database: nasha_mukti1_db

Database Schema
centers

id, name, address, state, city

contact_person, phone, email

capacity

created_at

beneficiaries

id, center_id

name, age, gender

addiction_type

admission_date

status

created_at

interventions

id, beneficiary_id

intervention_type

description

date

outcome

created_at

addiction_types

id, name, count

monthly_admissions

id, month, year, count

users

id, username, password

email

role (admin/client)

created_at

Usage
Admin Login

Username: admin

Password: admin123

Adding Centers

Admin adds centers using Add Center page.

Beneficiary Management

Register beneficiaries, track admissions, interventions, recovery.

Viewing Records

Filter by state, addiction type, status.

Statistics Dashboard

Interactive charts loaded via Chart.js.

Deployment

Already hosted at:
http://nashamukti.ct.ws/

To deploy on your server:

Upload files

Create database

Update db.php

Run setup_db.php

Maintenance

Take regular DB backups

Keep PHP & MySQL updated

Ensure update_stats.php runs correctly

License

MIT License
