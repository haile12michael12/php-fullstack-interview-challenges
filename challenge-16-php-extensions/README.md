# Challenge 16: PHP Extensions Development

## Description
This challenge introduces you to PHP extension development by creating a simple C extension that adds custom functionality to PHP. You'll learn the basics of the PHP extension API, memory management, and how to expose C functions to PHP userspace.

## Learning Objectives
- Understand the PHP extension architecture
- Write and compile C code for PHP extensions
- Handle PHP variables and types in C
- Implement custom PHP functions
- Manage memory in PHP extensions
- Debug extension development

## Requirements
- PHP 8.1+ with development headers
- C compiler (GCC or Clang)
- PHP development tools (phpize, php-config)
- Basic knowledge of C programming
- Understanding of PHP internals

## Features to Implement
1. Extension Structure
   - Basic extension skeleton
   - Module initialization functions
   - Function entry points
   - Module information

2. Custom Functions
   - Simple mathematical functions
   - String manipulation functions
   - Array processing functions
   - Resource management

3. Type Handling
   - Working with PHP variables in C
   - Type conversion between PHP and C
   - Memory management
   - Error handling in extensions

4. Advanced Features
   - Custom classes and objects
   - Resource types
   - Constants and globals
   - Stream wrappers

## Project Structure
```
challenge-16-php-extensions/
├── backend-php/
│   ├── ext/
│   │   └── myextension/
│   │       ├── config.m4
│   │       ├── php_myextension.h
│   │       ├── myextension.c
│   │       └── tests/
│   ├── public/
│   ├── src/
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   └── ExtensionDemo.jsx
│   │   └── services/
│   │       └── extensionService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Install PHP development headers:
   ```bash
   # Ubuntu/Debian
   sudo apt-get install php-dev
   
   # CentOS/RHEL
   sudo yum install php-devel
   
   # macOS with Homebrew
   brew install php
   ```
2. Navigate to the extension directory:
   ```bash
   cd backend-php/ext/myextension
   ```
3. Prepare the build environment:
   ```bash
   phpize
   ```
4. Configure the extension:
   ```bash
   ./configure
   ```
5. Compile the extension:
   ```bash
   make
   ```
6. Install the extension:
   ```bash
   sudo make install
   ```
7. Enable the extension in php.ini:
   ```ini
   extension=myextension.so
   ```
8. Test the extension:
   ```bash
   php -r "echo myextension_version();"
   ```

## API Functions
- `myextension_version()` - Returns the extension version
- `myextension_hello($name)` - Returns a greeting message
- `myextension_math_add($a, $b)` - Adds two numbers
- `myextension_string_reverse($str)` - Reverses a string
- `myextension_array_sum($arr)` - Sums array values

## Evaluation Criteria
- Successful compilation and installation of the extension
- Proper implementation of custom functions
- Correct handling of PHP types in C
- Memory management best practices
- Documentation and code quality
- Demonstration of extension functionality

## Resources
- [PHP Internals Book](https://www.phpinternalsbook.com/)
- [PHP Extension Writing Tutorial](https://www.php.net/manual/en/internals2.php)
- [Zend API Documentation](https://www.php.net/manual/en/internals2.funcs.php)