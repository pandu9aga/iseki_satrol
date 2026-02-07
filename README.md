# Iseki Satrol - Patrol Monitoring & Safety Management System

## Overview

**Iseki Satrol** is a robust management system designed to coordinate and monitor safety or security patrols. It provides a structured framework for scheduling patrols, managing team members, recording field findings ("Temuan"), and assigning performance scores.

The platform distinguishes between **Admin** (management/auditing) and **User** (operational/patrolling) roles to ensure accountability and streamlined reporting from the field to leadership.

## Key Features

### 1. Patrol & Team Management
*   **Patrol Scheduling**: Create and manage patrol routes and timing.
*   **Member Assignment**: Assign specific members to patrol teams.
*   **Bulk Member Import**: Fast project setup by importing member lists from Excel.

### 2. Finding (Temuan) Tracking
*   **Field Reporting**: Operators can report findings (issues or observations) directly during a patrol.
*   **Status Management**: Admins can track the lifecycle of a finding from discovery to resolution.
*   **PPT Export**: Automatically generate formatted PowerPoint presentations of findings for management reviews and safety meetings.

### 3. Scoring & Performance
*   **Patrol Scoring**: Assign scores ("Nilai") to patrol outcomes based on predefined criteria.
*   **Average Analytics**: Monitor patrol performance over time with automated average calculation per patrol type or team.

### 4. Role-Based Access
*   **Admin Dashboard**: Centralized view of all patrol activities, user management, and reporting tools.
*   **User/Patroller Interface**: Simplified view for field personnel to see assigned tasks and submit data.

## Technology Stack

### Backend
*   **Framework**: [Laravel 12.x](https://laravel.com)
*   **Language**: PHP ^8.2
*   **Database**: SQLite (Default) / MySQL Compatible
*   **Document Generation**:
    *   `phpoffice/phppresentation`: Specifically used for the Findings to PowerPoint export feature.
    *   `phpoffice/phpspreadsheet`: Used for Excel-based member imports and data exports.

### Frontend
*   **Build Tool**: [Vite](https://vitejs.dev)
*   **Styling**: [Tailwind CSS v4.0](https://tailwindcss.com)
*   **HTTP Client**: Axios

## Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone <repository-url>
    cd iseki_satrol
    ```

2.  **Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment**
    *   Copy `.env.example` to `.env`.
    *   Configure your database and application settings.

4.  **Initialization**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Frontend Build**
    ```bash
    npm run build
    ```

6.  **Run Server**
    ```bash
    php artisan serve
    ```
    Access the system at `http://localhost:8000`.

## Reporting Features

The system supports specialized reporting:
- **Finding PPT**: Export detailed findings into a presentation format for stakeholder reviews.
- **Performance Reports**: Track team effectiveness through automated scoring summaries.

## License

This project is proprietary.
