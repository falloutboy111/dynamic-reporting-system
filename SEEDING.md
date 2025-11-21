# Database Seeding Guide

## Production Seeding

Use the `db:seed-production` command to safely seed your production database with essential data.

### Basic Usage

```bash
# Seed only roles (production-safe)
php artisan db:seed-production

# Seed roles and create an admin user
php artisan db:seed-production --admin-email=admin@yourcompany.com --admin-password=SecurePassword123 --admin-name="Admin User"

# Force run in production (bypasses confirmation)
php artisan db:seed-production --force
```

### Options

- `--force`: Force the operation to run even in production (bypasses confirmation)
- `--admin-email`: Email address for the admin user
- `--admin-password`: Password for the admin user (must be at least 8 characters)
- `--admin-name`: Name for the admin user (defaults to "Admin User")

### What Gets Seeded

**Production Command (`db:seed-production`):**
- ✅ Roles (admin, user)
- ✅ Optional admin user (if email provided)

**Development Seeding (`db:seed`):**
- ✅ Roles (admin, user)
- ✅ Test admin user (admin@example.com / password)
- ✅ 5 test users
- ✅ 10 test organisations
- ✅ Journals, journal lines, and logs

### Examples

```bash
# Production: Create roles only
php artisan db:seed-production

# Production: Create roles + admin user (interactive password)
php artisan db:seed-production --admin-email=admin@company.com --admin-name="John Doe"

# Production: Create roles + admin user (all options)
php artisan db:seed-production \
  --admin-email=admin@company.com \
  --admin-password=SecurePass123! \
  --admin-name="John Doe"

# Development: Seed everything including test data
php artisan db:seed
```

### Safety Features

- Production environment requires confirmation (unless `--force` is used)
- Test data is never created in production
- Admin user creation is optional and secure
- Password validation (minimum 8 characters)

