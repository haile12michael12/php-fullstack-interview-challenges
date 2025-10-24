# Challenge 61: Template Engine

## Description
In this challenge, you'll build a template engine that can render dynamic HTML pages with variables, loops, and conditionals. This will help you understand how templating works in frameworks like Twig, Blade, and Smarty.

## Learning Objectives
- Understand template compilation and rendering
- Implement variable substitution
- Create control structures (loops, conditionals)
- Handle template inheritance and includes
- Implement template caching
- Support custom filters and functions

## Requirements
Create a template engine with the following features:

1. **Basic Rendering**:
   - Variable substitution with `{{ variable }}` syntax
   - HTML escaping for security
   - Template compilation to PHP code
   - Template caching for performance

2. **Control Structures**:
   - Conditional statements (`if`, `else`, `elseif`)
   - Loop constructs (`for`, `foreach`)
   - Loop variables (`loop.index`, `loop.first`, `loop.last`)
   - Break and continue statements

3. **Template Organization**:
   - Template inheritance with blocks
   - Include partial templates
   - Template layouts and sections
   - Template paths and namespaces

4. **Advanced Features**:
   - Custom filters and functions
   - Template macros and reusable components
   - Sandbox mode for untrusted templates
   - Debug mode with template information
   - Error handling with line numbers

## Features to Implement
- [ ] Variable substitution with `{{ }}` syntax
- [ ] Conditional statements with `{% if %}` syntax
- [ ] Loop constructs with `{% for %}` syntax
- [ ] Template inheritance with `{% block %}` and `{% extends %}`
- [ ] Include partial templates with `{% include %}`
- [ ] Template compilation and caching
- [ ] Custom filters and functions
- [ ] HTML escaping for security
- [ ] Error handling with line numbers
- [ ] Template debugging information

## Project Structure
```
backend-php/
├── src/
│   ├── Template/
│   │   ├── TemplateEngine.php
│   │   ├── TemplateCompiler.php
│   │   ├── TemplateCache.php
│   │   ├── TemplateLoader.php
│   │   └── Extension/
│   │       ├── FilterExtension.php
│   │       └── FunctionExtension.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── main.php
│   │   ├── partials/
│   │   │   └── header.php
│   │   ├── home.php
│   │   └── user/
│   │       ├── list.php
│   │       └── profile.php
│   └── Controllers/
├── public/
│   └── index.php
├── cache/
│   └── templates/
├── config/
│   └── template.php
└── vendor/

frontend-react/
├── src/
│   ├── components/
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## Template Syntax Examples
```
{# Variable substitution #}
<h1>Hello {{ name }}!</h1>

{# Conditional statements #}
{% if user.isAdmin %}
    <p>Welcome, admin!</p>
{% else %}
    <p>Welcome, user!</p>
{% endif %}

{# Loop constructs #}
<ul>
{% for user in users %}
    <li>{{ loop.index }}. {{ user.name }}</li>
{% endfor %}
</ul>

{# Template inheritance #}
{% extends "layouts/main.php" %}
{% block content %}
    <h1>Page Content</h1>
{% endblock %}
```

## Evaluation Criteria
- [ ] Template engine correctly renders variables
- [ ] Control structures work properly
- [ ] Template inheritance functions correctly
- [ ] Template caching improves performance
- [ ] HTML escaping prevents XSS attacks
- [ ] Custom filters and functions work
- [ ] Error handling provides useful information
- [ ] Code is well-organized and documented
- [ ] Tests cover template engine functionality

## Resources
- [Template Engine](https://en.wikipedia.org/wiki/Web_template_system)
- [Twig Documentation](https://twig.symfony.com/)
- [Blade Templates](https://laravel.com/docs/blade)
- [Smarty Template Engine](https://www.smarty.net/)