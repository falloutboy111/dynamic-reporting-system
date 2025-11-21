# Multi-Tenant Role-Based Authentication and Reporting System

## Setup Instructions

### 1. Database Migration

Run the migrations to set up the database structure:

```bash
php artisan migrate:fresh
```

### 2. Seed Roles and Admin User

Seed the roles and create a default admin user:

```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

This will create:
- Two roles: `admin` and `user`
- Default admin user:
  - Email: `admin@example.com`
  - Password: `password`

### 3. Access the Application

#### Unified Login Portal
- URL: `/login`
- The system automatically redirects to the appropriate dashboard based on your role

**Admin Access:**
- Email: `admin@example.com`
- Password: `password`
- Redirects to: `/admin/dashboard`

**User Access:**
- Email: `user0@example.com` (or user1-4)
- Password: `password`
- Redirects to: `/user/dashboard`

## Features Implemented

### Admin Features
1. **Organizations Management** (`/admin/organisations`)
   - Create, edit, and delete organizations
   - Configure database connections for each organization
   - Test database connections before saving
   - Activate/deactivate organizations

2. **Users Management** (`/admin/users`)
   - Create and manage users
   - Assign users to organizations
   - Set user roles (admin or user)
   - Admin users don't require organization assignment

3. **Report Builder** (`/admin/reports`)
   - Select organization and view database schema
   - Write and validate SQL queries (SELECT only)
   - Preview query results
   - Choose visualization type (table or chart)
   - Configure Chart.js charts (line, bar, pie, doughnut)
   - Save reports for organizations

### User Features
1. **Dashboard** (`/user/dashboard`)
   - View organization information
   - Quick access to reports
   - Recently updated reports

2. **Reports** (`/user/reports`)
   - View all reports for their organization
   - Filter and search reports
   - Click to view detailed reports

3. **Report Viewing** (`/user/reports/{id}`)
   - View report data in table or chart format
   - Refresh data
   - Export to CSV
   - Interactive Chart.js visualizations

## Security Features

### Authentication
- Separate login portals for admin and user roles
- Role-based middleware protecting routes
- Spatie Permission for role management

### Database Security
- Database credentials encrypted at rest using Laravel encryption
- SQL query validation (SELECT only)
- Multiple query blocking
- Dangerous keyword filtering
- Query timeout (30 seconds)
- Result set limit (10,000 rows)
- Read-only database access

### Data Protection
- Organization-level data isolation
- Users can only access their organization's reports
- Admins have full system access

## Technical Stack

- **Framework**: Laravel 11
- **UI**: Livewire 3.5+ with Flux UI
- **Authentication**: Laravel Breeze + Spatie Permission
- **Styling**: Tailwind CSS v4
- **Charts**: Chart.js 4.4.0
- **Database**: MySQL (with support for dynamic connections)

## Directory Structure

```
app/
├── Http/
│   └── Middleware/
│       ├── EnsureUserIsAdmin.php
│       └── EnsureUserIsRegularUser.php
├── Livewire/
│   ├── Admin/
│   │   ├── OrganisationManagement.php
│   │   ├── UserManagement.php
│   │   └── ReportBuilder.php
│   └── User/
│       ├── ReportsList.php
│       └── ReportView.php
├── Models/
│   ├── Organisation.php
│   ├── Report.php
│   └── User.php
└── Services/
    ├── DatabaseConnectionManager.php
    ├── SqlQueryValidator.php
    └── DatabaseSchemaDiscovery.php

resources/
└── views/
    ├── layouts/
    │   ├── admin.blade.php
    │   ├── user.blade.php
    │   └── guest.blade.php
    ├── livewire/
    │   ├── pages/
    │   │   ├── admin/auth/login.blade.php
    │   │   └── user/auth/login.blade.php
    │   ├── admin/
    │   │   ├── organisation-management.blade.php
    │   │   ├── user-management.blade.php
    │   │   └── report-builder.blade.php
    │   └── user/
    │       ├── reports-list.blade.php
    │       └── report-view.blade.php
    ├── admin/
    │   └── dashboard.blade.php
    └── user/
        └── dashboard.blade.php
```

## Usage Examples

### Creating an Organization (Admin)

1. Login to admin portal
2. Navigate to Organizations
3. Click "Add Organization"
4. Fill in organization name and database connection details
5. Test connection
6. Save

### Creating a User (Admin)

1. Navigate to Users Management
2. Click "Add User"
3. Fill in user details
4. Select role (admin or user)
5. For user role, select an organization
6. Save

### Building a Report (Admin)

1. Navigate to Reports
2. Click "Build New Report"
3. Select an organization
4. View the database schema on the left sidebar
5. Write your SQL query (SELECT only)
6. Click "Validate & Preview" to test
7. Choose visualization type:
   - **Table**: Simple tabular display
   - **Chart**: Configure chart type and columns
8. Save report

### Viewing Reports (User)

1. Login to user portal
2. Navigate to Reports
3. Click on any report card
4. View the visualization
5. Export data if needed

## Environment Configuration

Ensure your `.env` file has proper database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Troubleshooting

### Database Connection Issues
- Verify organization database credentials
- Ensure network connectivity to remote databases
- Check firewall rules
- Verify database user has SELECT permissions

### Query Validation Errors
- Only SELECT statements are allowed
- No multiple queries (separated by semicolons)
- No dangerous keywords (INSERT, UPDATE, DELETE, etc.)

### Chart Not Displaying
- Ensure chart configuration has valid column names
- Column names must match exactly with query results
- Data column should contain numeric values

## Future Enhancements

The following features were identified but deferred:
- Advanced permissions beyond admin/user roles
- Multi-database type support (PostgreSQL, MongoDB, etc.)
- Report scheduling and automated delivery
- Advanced chart customization UI
- Audit logging of report access
- Report sharing between organizations
- Query optimization and caching layer
- Advanced SQL query builder UI
- Real-time collaborative report editing
- PDF/Excel export (requires additional packages)

To implement PDF/Excel export, install:
```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
```

## Support

For issues or questions, contact your system administrator.

