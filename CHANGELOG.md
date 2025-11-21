# Changelog

## [1.0.1] - 2025-11-21

### Changed
- **Unified Login Experience**: Replaced separate admin and user login pages with a single unified login portal
  - Single login page at `/login` for all users
  - Automatic role detection and redirection after authentication
  - Admins automatically redirected to `/admin/dashboard`
  - Users automatically redirected to `/user/dashboard`
  - Improved UX by removing the need to remember different login URLs

### Removed
- `/admin/login` route (consolidated into `/login`)
- `/user/login` route (consolidated into `/login`)
- Separate admin and user login view files

### Technical Details
- Updated `resources/views/livewire/pages/auth/login.blade.php` to handle role-based redirection
- Modified `routes/web.php` to use single login route
- Authentication logic now checks user role after login and redirects appropriately
- Maintains all security features and role-based access control

## [1.0.0] - 2025-11-21

### Added
- Initial release
- Multi-tenant role-based authentication system
- Organization management with encrypted database credentials
- User management with role assignment
- Dynamic reporting system with SQL query builder
- Chart.js visualizations (line, bar, pie, doughnut)
- Table visualizations with pagination
- CSV export functionality
- Database schema discovery
- SQL query validation (SELECT only)
- Admin and user dashboards with separate layouts
- Flux UI components with Tailwind CSS v4
- Spatie Permission integration for role management
- Complete CRUD operations for organizations, users, and reports

