# Challenge 94: PHP Internals

## Description
In this capstone challenge, you'll dive deep into PHP internals by modifying the PHP source code to add a custom opcode. This advanced challenge will give you insight into how PHP works under the hood, including its lexer, parser, compiler, and executor components. You'll learn about the Zend Engine architecture and gain hands-on experience with extending the PHP language itself.

## Learning Objectives
- Understand PHP internals and Zend Engine architecture
- Learn about lexical analysis and parsing in PHP
- Explore opcode generation and execution
- Modify PHP source code to add custom functionality
- Compile PHP from source with custom changes
- Debug and test internal PHP modifications
- Understand memory management in PHP internals
- Gain experience with C programming in PHP context

## Requirements

### Core Features
1. **PHP Source Code Familiarity**
   - Download and understand PHP source code structure
   - Identify key components: lexer, parser, compiler, executor
   - Understand Zend Engine architecture
   - Learn about PHP's memory management system

2. **Custom Opcode Implementation**
   - Design a new opcode that extends PHP functionality
   - Modify lexer to recognize new syntax (if needed)
   - Update parser to handle new constructs
   - Implement opcode handler in executor
   - Register new opcode in Zend Engine

3. **Compilation and Building**
   - Set up development environment for PHP compilation
   - Configure and compile PHP with custom changes
   - Test custom opcode functionality
   - Debug compilation issues and runtime errors

4. **Testing and Validation**
   - Write tests for custom opcode functionality
   - Validate memory management and resource cleanup
   - Test edge cases and error conditions
   - Benchmark performance impact of changes

### Advanced Features
1. **Advanced Internals Concepts**
   - Implement garbage collection awareness
   - Handle type juggling and coercion
   - Integrate with PHP's exception system
   - Support debugging and profiling tools

2. **Documentation and Examples**
   - Document new functionality with PHPDoc
   - Create usage examples and tutorials
   - Write comprehensive test cases
   - Prepare installation and setup guides

## Project Structure
```
challenge-94-php-internals/
├── backend-php/
│   ├── src/
│   │   └── Extension/
│   │       ├── php_custom_extension.h
│   │       ├── custom_extension.c
│   │       └── tests/
│   ├── config/
│   ├── public/
│   │   └── test-extension.php
│   ├── Dockerfile.build
│   ├── Dockerfile.runtime
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── PHPRuntimeInfo.jsx
│   │   │   └── ExtensionDemo.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── php-src-modified/
│   ├── Zend/
│   │   ├── zend_language_parser.y
│   │   ├── zend_compile.h
│   │   ├── zend_vm_def.h
│   │   └── zend_execute.c
│   ├── ext/
│   │   └── custom_opcode/
│   │       ├── config.m4
│   │       ├── php_custom_opcode.h
│   │       └── custom_opcode.c
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- C compiler (GCC/Clang)
- Autoconf, automake, libtool
- PHP development headers
- Docker (for containerized builds)
- Git
- Linux or macOS development environment (WSL recommended for Windows)

### Development Environment Setup
1. Clone PHP source code:
   ```bash
   git clone https://github.com/php/php-src.git
   cd php-src
   ```

2. Install build dependencies (Ubuntu/Debian):
   ```bash
   sudo apt-get update
   sudo apt-get install build-essential autoconf automake libtool re2c bison
   ```

3. Install PHP development dependencies:
   ```bash
   sudo apt-get install libxml2-dev libssl-dev libcurl4-openssl-dev
   ```

### Building Custom PHP
1. Navigate to the modified PHP source directory
2. Generate configure script:
   ```bash
   ./buildconf
   ```
3. Configure build with custom extension:
   ```bash
   ./configure --enable-custom-opcode
   ```
4. Compile PHP:
   ```bash
   make
   ```

### Testing Custom Opcode
1. Run tests for custom functionality:
   ```bash
   ./sapi/cli/php -f test-custom-opcode.php
   ```
2. Run full test suite:
   ```bash
   make test
   ```

## Implementation Details

### Understanding PHP Internals
The PHP interpreter consists of several key components:
1. **Lexer (Lexical Analyzer)** - Converts source code into tokens
2. **Parser** - Creates Abstract Syntax Tree (AST) from tokens
3. **Compiler** - Generates opcodes from AST
4. **Executor** - Runs opcodes through Zend Virtual Machine

### Adding a Custom Opcode
To add a custom opcode:

1. **Define the Opcode**
   In `Zend/zend_vm_opcodes.h`, add your opcode:
   ```c
   #define ZEND_CUSTOM_OPCODE 250
   ```

2. **Implement the Handler**
   In `Zend/zend_vm_def.h`, define the opcode handler:
   ```c
   ZEND_VM_HANDLER(250, ZEND_CUSTOM_OPCODE, CONST|TMPVAR|CV, ANY)
   {
       // Implementation here
   }
   ```

3. **Update the Parser**
   Modify `Zend/zend_language_parser.y` to recognize new syntax:
   ```yacc
   custom_opcode:
       T_CUSTOM_KEYWORD expr { $$ = zend_ast_create(ZEND_CUSTOM_OPCODE, $2); }
   ;
   ```

4. **Register the Extension**
   Create extension files in `ext/custom_opcode/`:
   - `config.m4` - Build configuration
   - `php_custom_opcode.h` - Header file
   - `custom_opcode.c` - Extension implementation

### Memory Management
When working with PHP internals, proper memory management is crucial:
1. Use PHP's memory allocator (`emalloc`, `efree`, `estrdup`)
2. Handle reference counting for zvals
3. Implement proper cleanup in destructors
4. Avoid memory leaks and double-free errors

### Frontend Integration
The React frontend should:
1. Display PHP runtime information
2. Demonstrate custom opcode functionality
3. Show performance comparisons
4. Provide educational content about PHP internals

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of custom opcode
   - Correct integration with PHP internals
   - Accurate memory management

2. **Depth of Understanding** (25%)
   - Clear explanation of internals concepts
   - Well-documented code changes
   - Proper handling of edge cases

3. **Build and Compilation** (20%)
   - Successful compilation of modified PHP
   - Proper extension registration
   - Working test cases

4. **Educational Value** (15%)
   - Clear documentation and examples
   - Well-structured learning materials
   - Demonstrates key internals concepts

5. **Code Quality** (10%)
   - Clean, well-organized C code
   - Following PHP internals coding standards
   - Proper error handling and validation

## Resources
1. [PHP Internals Book](https://www.phpinternalsbook.com/)
2. [PHP Source Code Repository](https://github.com/php/php-src)
3. [Zend Engine Documentation](https://www.php.net/manual/en/internals.php)
4. [Writing PHP Extensions](https://www.php.net/manual/en/internals2.php)
5. [PHP Compiler Design](https://nikic.github.io/2017/04/14/PHP-compiler-design.html)
6. [Understanding the Zend Engine](https://devzone.zend.com/277/zend-engine-php-internals-part-1/)
7. [Extending PHP with C](https://www.sitepoint.com/extending-php-with-c/)
8. [PHP Memory Management](https://www.php.net/manual/en/internals2.memory.php)

## Stretch Goals
1. Implement a JIT compilation optimization for your opcode
2. Add debugging support for your custom opcode
3. Create a PHP extension that interfaces with your custom opcode
4. Benchmark performance improvements compared to userland implementations
5. Add profiling capabilities to measure opcode execution
6. Implement thread-safe versions of your opcode for ZTS builds