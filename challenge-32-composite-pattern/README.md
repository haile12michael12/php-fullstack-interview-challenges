# Challenge 32: Composite Pattern

## Description
This challenge focuses on implementing the Composite design pattern to create a hierarchical menu system with composite pattern. You'll learn to compose objects into tree structures and treat individual objects and compositions uniformly.

## Learning Objectives
- Implement the Composite design pattern
- Create tree-like structures of objects
- Treat individual and composite objects uniformly
- Understand component-leaf-composite relationships
- Implement recursive operations on tree structures
- Manage hierarchical data effectively

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of tree data structures

## Features to Implement
1. Component Interface
   - Common operations for all components
   - Child management methods
   - Navigation methods

2. Leaf Classes
   - Menu items
   - Action items
   - Link items
   - Separator items

3. Composite Classes
   - Menu containers
   - Submenu groups
   - Toolbar containers
   - Panel containers

4. Advanced Features
   - Menu rendering
   - Permission-based visibility
   - Dynamic menu building
   - Menu persistence

## Project Structure
```
challenge-32-composite-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Component/
│   │   │   ├── MenuComponentInterface.php
│   │   │   ├── MenuItem.php
│   │   │   ├── MenuSeparator.php
│   │   │   └── MenuLink.php
│   │   ├── Composite/
│   │   │   ├── MenuComposite.php
│   │   │   ├── SubMenu.php
│   │   │   ├── Toolbar.php
│   │   │   └── Panel.php
│   │   └── Service/
│   │       └── MenuService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── MenuBuilder.jsx
│   │   │   └── MenuRenderer.jsx
│   │   └── services/
│   │       └── menuService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your settings
4. Start the development server with `php server.php`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the frontend development server

## API Endpoints
- `GET /api/menus` - Get all menus
- `GET /api/menus/{id}` - Get specific menu
- `POST /api/menus` - Create new menu
- `PUT /api/menus/{id}` - Update menu
- `DELETE /api/menus/{id}` - Delete menu
- `GET /api/menus/{id}/render` - Render menu structure

## Evaluation Criteria
- Proper implementation of Composite pattern interfaces
- Effective tree structure management
- Uniform treatment of components and composites
- Robust child management operations
- Clean recursive operation implementation
- Comprehensive test coverage

## Resources
- [Composite Pattern](https://en.wikipedia.org/wiki/Composite_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Structural/Composite/README.html)
- [Tree Data Structures](https://en.wikipedia.org/wiki/Tree_(data_structure))