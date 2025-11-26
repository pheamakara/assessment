# Server Assessment System

A comprehensive server infrastructure management system for tracking servers, managing deployment checklists, generating reports, and maintaining audit trails.

## Features

- User Management & Authentication with 5 roles (ADMIN, CLOUD_MANAGER, CLOUD_ENGINEER, SECURITY, AUDITOR)
- Server Management (CRUD operations with advanced filtering and Excel import/export)
- Deployment Checklist System with workflow (DRAFT → PENDING_SECURITY → PENDING_CLOUD → APPROVED/REJECTED)
- Executive Dashboard with charts and statistics
- Advanced Reporting System with scheduling
- Audit Logging for all actions
- Help Documentation system
- Notifications
- Settings management
- Profile management with password change

## Tech Stack

- PHP 8.1
- MySQL 8.0
- Bootstrap 5
- Chart.js

## Installation

### Using Docker (Recommended)

1. Clone the repository:
   ```
   git clone <repository-url>
   cd server-assessment-system
   ```

2. Start the application using Docker Compose:
   ```
   docker-compose up -d
   ```

3. Run the installation script:
   ```
   docker-compose exec web php install.php
   ```

4. Access the application at http://localhost:8000

### Manual Installation

1. Install the required dependencies:
   - PHP 8.1 or higher
   - MySQL 8.0 or higher
   - Apache or Nginx web server

2. Clone the repository:
   ```
   git clone <repository-url>
   cd server-assessment-system
   ```

3. Configure your web server to point to the `public` directory

4. Create a MySQL database:
   ```
   CREATE DATABASE server_assessment;
   ```

5. Run the installation script:
   ```
   php install.php
   ```

## Default Credentials

- Username: `admin`
- Password: `admin123`

## Project Structure

```
server-assessment-system/
├── app/                 # Application source code
│   ├── controllers/     # Controller classes
│   ├── models/          # Model classes
│   ├── views/           # View templates
│   ├── config/          # Configuration files
│   └── core/            # Core framework files
├── public/              # Publicly accessible files
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── uploads/         # Uploaded files
├── database/            # Database schema and migrations
├── tests/               # Test scripts
├── Dockerfile           # Docker configuration
├── docker-compose.yml   # Docker Compose configuration
├── install.php          # Installation script
└── README.md            # This file
```

## Testing

Run the test script to verify the installation:

```
php tests/test_app.php
```

## License

This project is licensed under the MIT License.
