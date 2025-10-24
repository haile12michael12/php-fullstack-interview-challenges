# Challenge 62: Form Handling

## Description
In this challenge, you'll build a comprehensive form handling system that manages validation, sanitization, and processing of user input. This is a critical component of web applications for collecting and processing user data securely.

## Learning Objectives
- Understand form processing workflows
- Implement validation and sanitization
- Handle file uploads securely
- Create form builders and helpers
- Manage CSRF protection
- Implement form persistence and re-population

## Requirements
Create a form handling system with the following features:

1. **Form Processing**:
   - Request data extraction and validation
   - Data sanitization and filtering
   - Error collection and reporting
   - Form re-population after validation failures
   - Redirect-after-post pattern implementation

2. **Validation System**:
   - Built-in validation rules (required, email, numeric, etc.)
   - Custom validation rules
   - Conditional validation
   - Validation rule chaining
   - Error message customization

3. **Security Features**:
   - CSRF token generation and validation
   - File upload validation and security
   - Input sanitization and escaping
   - Rate limiting for form submissions
   - Honeypot fields for bot prevention

4. **Form Building**:
   - Form builder classes for dynamic forms
   - HTML helper methods for form elements
   - Form macro system for reusable components
   - Client-side validation integration
   - Form theme and styling support

## Features to Implement
- [ ] Form class with validation and processing
- [ ] Validation rules (required, email, min/max, etc.)
- [ ] Custom validation rule support
- [ ] CSRF protection tokens
- [ ] File upload handling and validation
- [ ] Form builder for dynamic forms
- [ ] HTML helper methods for form elements
- [ ] Error handling and display
- [ ] Form re-population after validation failures
- [ ] Redirect-after-post pattern

## Project Structure
```
backend-php/
├── src/
│   ├── Form/
│   │   ├── Form.php
│   │   ├── FormBuilder.php
│   │   ├── Validator.php
│   │   ├── ValidationError.php
│   │   ├── Rules/
│   │   │   ├── RequiredRule.php
│   │   │   ├── EmailRule.php
│   │   │   ├── MinLengthRule.php
│   │   │   └── CustomRule.php
│   │   └── Html/
│   │       ├── FormHelper.php
│   │       └── HtmlBuilder.php
│   ├── Http/
│   │   ├── Request.php
│   │   └── CsrfToken.php
│   └── Controllers/
├── public/
│   └── index.php
├── storage/
│   └── uploads/
├── config/
│   └── forms.php
└── vendor/

frontend-react/
├── src/
│   ├── components/
│   │   └── forms/
│   │       ├── Form.jsx
│   │       ├── Input.jsx
│   │       └── Validation.jsx
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

## Example Forms
```
// User Registration Form
[
    'username' => ['required', 'min:3', 'max:20'],
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],
    'confirm_password' => ['required', 'same:password']
]

// Contact Form
[
    'name' => ['required', 'min:2'],
    'email' => ['required', 'email'],
    'subject' => ['required', 'min:5'],
    'message' => ['required', 'min:10']
]
```

## API Endpoints
- `GET /forms/contact` - Display contact form
- `POST /forms/contact` - Process contact form submission
- `GET /forms/register` - Display registration form
- `POST /forms/register` - Process registration form submission
- `POST /forms/validate` - Validate form data without processing

## Evaluation Criteria
- [ ] Form validation works correctly with built-in rules
- [ ] Custom validation rules can be added
- [ ] CSRF protection prevents cross-site request forgery
- [ ] File uploads are handled securely
- [ ] Form re-population works after validation failures
- [ ] Error messages are displayed properly
- [ ] Form builder creates correct HTML markup
- [ ] Code is well-organized and documented
- [ ] Tests cover form handling functionality

## Resources
- [Form Validation](https://en.wikipedia.org/wiki/Data_validation)
- [Cross-Site Request Forgery](https://owasp.org/www-community/attacks/csrf)
- [Laravel Validation](https://laravel.com/docs/validation)
- [Symfony Form Component](https://symfony.com/doc/current/forms.html)