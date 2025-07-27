Multi-Tenant CMS with Laravel

A comprehensive multi-tenant Content Management System built with Laravel, featuring single domain multi-tenancy, blog post management, rich text editing, category management, and featured image uploads.

Features

Multi-Tenancy

•
Single Domain Multi-Tenancy: Multiple tenants on one domain using subdomains (e.g., tenant1.app.com, tenant2.app.com)

•
Data Isolation: Complete separation of tenant data using tenant_id scoping

•
Tenant Middleware: Automatic tenant resolution based on subdomain

•
Cross-Tenant Protection: Prevents data leakage between tenants

Content Management

•
Blog Posts: Create, edit, delete, and manage blog posts

•
Rich Text Editor: TipTap-powered WYSIWYG editor with formatting options

•
Categories: Organize posts with categories and taxonomy management

•
Featured Images: Upload and manage featured images for posts

•
Post Status: Draft, Published, and Archived status management

•
Sorting & Filtering: Sort posts by created_at, updated_at, title, status, and category

User Interface

•
Modern Design: Clean, responsive interface built with TailwindCSS

•
Dashboard: Tenant-specific dashboard with statistics and quick actions

•
Mobile Responsive: Works seamlessly on desktop and mobile devices

•
Interactive Elements: Hover states, transitions, and micro-interactions

API Support

•
RESTful API: Complete REST API for all resources

•
JSON Responses: Consistent JSON response format

•
API Authentication: Ready for API authentication integration

•
File Upload API: Endpoints for image uploads

File Management

•
Image Uploads: Support for featured images and editor images

•
File Validation: Image type and size validation

•
Storage Management: Organized file storage with tenant separation

•
Media Library Integration: Using Spatie Media Library for advanced file handling

Installation

Prerequisites

•
PHP 8.1 or higher

•
Composer

•
MySQL or PostgreSQL

•
Node.js & NPM (for frontend assets)

Setup Instructions

1.
Clone the repository

2.
Install dependencies

3.
Environment configuration

4.
Database configuration
Update your .env file with database credentials:

5.
Run migrations

6.
Seed demo data

7.
Create storage link

8.
Start the development server

Demo Tenants

The seeder creates two demo tenants:

TechCorp Blog

•
Domain: techcorp.test

•
User: john@techcorp.test (password: password)

•
Content: Technology and development focused blog posts

•
Categories: Technology, Development, Artificial Intelligence

FoodieWorld

•
Domain: foodieworld.test

•
User: jane@foodieworld.test (password: password)

•
Content: Food, recipes, and restaurant reviews

•
Categories: Recipes, Restaurant Reviews, Cooking Tips

Usage

Accessing Tenants

To test the multi-tenancy locally, add these entries to your /etc/hosts file:

Plain Text


127.0.0.1 techcorp.test
127.0.0.1 foodieworld.test


Then access:

•
http://techcorp.test:8000 (if using php artisan serve)

•
http://foodieworld.test:8000

Creating Posts

1.
Navigate to the Posts section

2.
Click "New Post"

3.
Fill in the title, select a category, and set status

4.
Use the rich text editor to create content

5.
Optionally upload a featured image

6.
Save as draft or publish immediately

Managing Categories

1.
Go to Categories section

2.
Create new categories with name and description

3.
Set categories as active/inactive

4.
Edit or delete existing categories

API Usage

The API endpoints are available at /api/ and include:

•
GET /api/posts - List all posts

•
POST /api/posts - Create a new post

•
GET /api/posts/{id} - Get a specific post

•
PUT /api/posts/{id} - Update a post

•
DELETE /api/posts/{id} - Delete a post

•
GET /api/categories - List all categories

•
POST /api/categories - Create a new category

All API endpoints respect tenant scoping automatically.

Architecture

Multi-Tenancy Implementation

The multi-tenancy is implemented using:

1.
Tenant Model: Stores tenant information (name, domain, subdomain, settings)

2.
Tenant Middleware: Resolves current tenant based on request domain

3.
Global Scopes: Automatically filters queries by tenant_id

4.
Service Provider: Registers tenant scoping globally

5.
Model Traits: Automatically sets tenant_id on model creation

Database Schema

Key tables:

•
tenants - Tenant information

•
users - Users (scoped by tenant)

•
categories - Post categories (scoped by tenant)

•
posts - Blog posts (scoped by tenant)

•
media - File uploads (via Spatie Media Library)

File Structure

Plain Text


app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/           # API controllers
│   │   ├── CategoryController.php
│   │   ├── PostController.php
│   │   ├── TenantController.php
│   │   └── FileUploadController.php
│   └── Middleware/
│       └── TenantMiddleware.php
├── Models/
│   ├── Tenant.php
│   ├── Category.php
│   ├── Post.php
│   └── User.php
├── Providers/
│   └── TenantServiceProvider.php
└── Traits/
    └── HandlesFileUploads.php

resources/views/
├── layouts/
│   └── app.blade.php
├── posts/
├── categories/
└── tenant/

tests/Feature/
├── TenantScopingTest.php
├── ApiEndpointTest.php
├── PostCrudTest.php
└── CategoryCrudTest.php


Testing

Run the test suite to ensure everything works correctly:

Bash


# Run all tests
php artisan test

# Run specific test files
php artisan test tests/Feature/TenantScopingTest.php
php artisan test tests/Feature/ApiEndpointTest.php


Test Coverage

The test suite includes:

•
Tenant Scoping Tests: Ensures data isolation between tenants

•
API Endpoint Tests: Tests all API endpoints with proper responses

•
CRUD Operation Tests: Tests create, read, update, delete operations

•
Cross-Tenant Protection: Verifies tenants cannot access each other's data

Security Features

•
Data Isolation: Complete separation of tenant data

•
Input Validation: Comprehensive validation on all inputs

•
File Upload Security: Validation of file types and sizes

•
CSRF Protection: Built-in Laravel CSRF protection

•
SQL Injection Prevention: Using Eloquent ORM prevents SQL injection

•
XSS Protection: Proper output escaping in views

Performance Considerations

•
Database Indexing: Proper indexes on tenant_id and frequently queried fields

•
Eager Loading: Relationships are eager loaded to prevent N+1 queries

•
Caching: Ready for Redis/Memcached integration

•
File Storage: Organized file storage structure

•
Query Optimization: Efficient queries with proper scoping

Customization

Adding New Tenant-Scoped Models

1.
Create the model with tenant_id field

2.
Add the tenant relationship

3.
Use the global scope for automatic filtering

4.
Add to TenantServiceProvider if needed

Extending the Rich Text Editor

The TipTap editor can be extended with additional features:

•
Tables

•
Code blocks

•
Mathematical formulas

•
Collaborative editing

Custom Themes

The UI is built with TailwindCSS and can be easily customized:

•
Modify the color palette

•
Add custom components

•
Create tenant-specific themes

Deployment

Production Setup

1.
Set up your web server (Apache/Nginx)

2.
Configure environment variables

3.
Run migrations and optimizations:

4.
Set up file storage (local or cloud)

5.
Configure subdomain routing

Environment Variables

Key environment variables for production:

Plain Text


APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# File Storage
FILESYSTEM_DISK=public
# or for S3:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=your-key
# AWS_SECRET_ACCESS_KEY=your-secret
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=your-bucket


Contributing

1.
Fork the repository

2.
Create a feature branch

3.
Make your changes

4.
Add tests for new functionality

5.
Ensure all tests pass

6.
Submit a pull request

License

This project is open-sourced software licensed under the MIT license.

Support

For support and questions:

•
Create an issue on GitHub

•
Check the documentation

•
Review the test files for usage examples

Changelog

Version 1.0.0

•
Initial release

•
Multi-tenant architecture

•
Blog post management

•
Rich text editor integration

•
Category management

•
Featured image uploads

•
Comprehensive test suite

•
API endpoints

•
Demo data and seeders

