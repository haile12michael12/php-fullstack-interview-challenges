# Challenge 96: Extension Development Advanced

## Description
In this challenge, you'll create complex PHP extensions with thread safety. Building on the basics of PHP extension development, you'll implement advanced features including thread-safe operations, memory management, object handlers, and integration with external libraries. This challenge will deepen your understanding of the Zend Engine and prepare you to build production-ready PHP extensions.

## Learning Objectives
- Master advanced PHP extension development techniques
- Implement thread-safe operations and resource management
- Create custom object handlers and data structures
- Integrate with external C libraries
- Implement proper memory management in extensions
- Handle complex data types and arrays
- Build thread-safe global variables and resources
- Understand Zend Engine internals and APIs

## Requirements

### Core Features
1. **Thread-Safe Operations**
   - Implement thread-safe global variables
   - Create thread-safe resource management
   - Handle concurrent access to shared data
   - Implement proper locking mechanisms
   - Manage thread-local storage

2. **Advanced Data Handling**
   - Create custom object handlers
   - Implement complex data structure support
   - Handle arrays and hash tables efficiently
   - Manage string and binary data
   - Implement custom iterator support

3. **External Library Integration**
   - Integrate with C libraries using FFI
   - Create bindings for external APIs
   - Handle library initialization and cleanup
   - Manage library dependencies
   - Implement error handling for external calls

4. **Memory Management**
   - Implement custom memory allocators
   - Handle garbage collection integration
   - Manage persistent and request-local memory
   - Implement memory pooling
   - Handle memory leaks and debugging

### Implementation Details
1. **Extension Structure**
   ```c
   // my_extension.c
   #include "php.h"
   #include "ext/standard/info.h"
   
   // Extension module entry
   zend_module_entry my_extension_module_entry = {
       STANDARD_MODULE_HEADER,
       "my_extension",
       my_extension_functions,
       PHP_MINIT(my_extension),
       PHP_MSHUTDOWN(my_extension),
       PHP_RINIT(my_extension),
       PHP_RSHUTDOWN(my_extension),
       PHP_MINFO(my_extension),
       PHP_MY_EXTENSION_VERSION,
       STANDARD_MODULE_PROPERTIES
   };
   ```

2. **Thread-Safe Resource Management**
   ```c
   // Thread-safe resource handling
   typedef struct {
       // Resource data
   } my_resource_t;
   
   static int le_my_resource;
   
   static void my_resource_dtor(zend_resource *rsrc)
   {
       my_resource_t *resource = (my_resource_t *) rsrc->ptr;
       // Cleanup resource
       efree(resource);
   }
   ```

## Project Structure
```
challenge-96-extension-advanced/
├── backend-php/
│   ├── src/
│   │   ├── Extension/
│   │   │   ├── php_my_extension.h
│   │   │   ├── my_extension.c
│   │   │   ├── config.m4
│   │   │   ├── config.w32
│   │   │   └── tests/
│   │   │       ├── 001.phpt
│   │   │       ├── 002.phpt
│   │   │       └── thread_safety.phpt
│   │   └── Library/
│   │       ├── ExternalLibraryWrapper.php
│   │       └── FFIIntegration.php
│   ├── config/
│   ├── public/
│   │   └── test-extension.php
│   ├── Dockerfile.build
│   ├── Dockerfile.runtime
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ExtensionDemo.jsx
│   │   │   ├── PerformanceTest.jsx
│   │   │   └── ThreadSafetyDemo.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP development headers and build tools
- C compiler (GCC/Clang)
- Autoconf, automake, libtool
- Docker (for containerized builds)
- Git

### Development Environment Setup
1. Install PHP development dependencies (Ubuntu/Debian):
   ```bash
   sudo apt-get update
   sudo apt-get install php-dev build-essential autoconf automake libtool
   ```

2. Install additional tools:
   ```bash
   sudo apt-get install valgrind gdb
   ```

### Building the Extension
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-96-extension-advanced/backend-php) directory
2. Generate configure script:
   ```bash
   phpize
   ```
3. Configure the extension:
   ```bash
   ./configure
   ```
4. Compile the extension:
   ```bash
   make
   ```
5. Install the extension:
   ```bash
   sudo make install
   ```

### Testing the Extension
1. Run basic tests:
   ```bash
   make test
   ```
2. Test thread safety:
   ```bash
   # Run with multiple threads
   ab -n 1000 -c 10 http://localhost/test-extension.php
   ```

## API Endpoints

### Extension Testing
- **GET** `/extension/info` - Get extension information
- **POST** `/extension/test` - Run extension functionality tests
- **GET** `/extension/thread-test` - Test thread safety
- **POST** `/extension/benchmark` - Run performance benchmarks

## Implementation Details

### Advanced Extension Development

1. **Thread-Safe Global Variables**
   ```c
   // Thread-safe global variable management
   #ifdef ZTS
   // Thread-safe implementation
   static MUTEX_T my_mutex;
   
   #define MY_G(v) TSRMG(my_globals_id, zend_my_globals *, v)
   #else
   // Single-thread implementation
   #define MY_G(v) (my_globals.v)
   #endif
   
   // Initialize thread safety
   PHP_MINIT_FUNCTION(my_extension)
   {
   #ifdef ZTS
       my_mutex = tsrm_mutex_alloc();
   #endif
       return SUCCESS;
   }
   
   // Cleanup thread safety
   PHP_MSHUTDOWN_FUNCTION(my_extension)
   {
   #ifdef ZTS
       tsrm_mutex_free(my_mutex);
   #endif
       return SUCCESS;
   }
   ```

2. **Custom Object Handlers**
   ```c
   // Custom object structure
   typedef struct {
       zend_object std;
       // Custom properties
       long custom_value;
       char *custom_string;
   } my_object;
   
   // Object handlers
   static zend_object_handlers my_object_handlers;
   
   // Object creation
   static zend_object *my_object_create(zend_class_entry *class_type)
   {
       my_object *intern = ecalloc(1, sizeof(my_object) + zend_object_properties_size(class_type));
       
       zend_object_std_init(&intern->std, class_type);
       object_properties_init(&intern->std, class_type);
       
       intern->std.handlers = &my_object_handlers;
       
       return &intern->std;
   }
   
   // Custom method implementation
   PHP_METHOD(MyClass, customMethod)
   {
       my_object *obj = (my_object *) Z_OBJ_P(getThis());
       
       // Access custom properties
       RETURN_LONG(obj->custom_value);
   }
   
   // Initialize object handlers
   static void my_object_init_handlers(void)
   {
       memcpy(&my_object_handlers, zend_get_std_object_handlers(), sizeof(zend_object_handlers));
       my_object_handlers.offset = XtOffsetOf(my_object, std);
       my_object_handlers.free_obj = my_object_free;
       my_object_handlers.clone_obj = my_object_clone;
   }
   ```

3. **Resource Management with Thread Safety**
   ```c
   // Resource structure
   typedef struct {
       void *data;
       size_t size;
       int refcount;
   #ifdef ZTS
       MUTEX_T mutex;
   #endif
   } my_resource;
   
   // Resource destructor
   static void my_resource_dtor(zend_resource *rsrc)
   {
       my_resource *resource = (my_resource *) rsrc->ptr;
       
   #ifdef ZTS
       tsrm_mutex_lock(resource->mutex);
   #endif
       
       if (--resource->refcount <= 0) {
           // Free resource data
           if (resource->data) {
               efree(resource->data);
           }
           
       #ifdef ZTS
           tsrm_mutex_unlock(resource->mutex);
           tsrm_mutex_free(resource->mutex);
       #endif
           
           efree(resource);
       } else {
       #ifdef ZTS
           tsrm_mutex_unlock(resource->mutex);
       #endif
       }
   }
   
   // Create resource
   PHP_FUNCTION(my_create_resource)
   {
       my_resource *resource = emalloc(sizeof(my_resource));
       resource->data = NULL;
       resource->size = 0;
       resource->refcount = 1;
       
   #ifdef ZTS
       resource->mutex = tsrm_mutex_alloc();
   #endif
       
       RETURN_RES(zend_register_resource(resource, le_my_resource));
   }
   
   // Use resource
   PHP_FUNCTION(my_use_resource)
   {
       zval *z_resource;
       my_resource *resource;
       
       if (zend_parse_parameters(ZEND_NUM_ARGS(), "r", &z_resource) == FAILURE) {
           RETURN_FALSE;
       }
       
       resource = (my_resource *) zend_fetch_resource(Z_RES_P(z_resource), "My Resource", le_my_resource);
       if (!resource) {
           RETURN_FALSE;
       }
       
   #ifdef ZTS
       tsrm_mutex_lock(resource->mutex);
   #endif
       
       // Use resource safely
       resource->refcount++;
       
   #ifdef ZTS
       tsrm_mutex_unlock(resource->mutex);
   #endif
       
       RETURN_TRUE;
   }
   ```

4. **FFI Integration**
   ```php
   // PHP code using FFI to call C functions
   class ExternalLibraryWrapper
   {
       private \FFI $ffi;
       
       public function __construct()
       {
           $this->ffi = \FFI::cdef("
               typedef struct {
                   int id;
                   char* name;
               } external_object_t;
               
               external_object_t* create_object(int id, const char* name);
               void destroy_object(external_object_t* obj);
               int get_object_id(external_object_t* obj);
           ", "libexternal.so");
       }
       
       public function createObject(int $id, string $name): ExternalObject
       {
           $cName = \FFI::new("char[" . (strlen($name) + 1) . "]");
           \FFI::memcpy($cName, $name, strlen($name));
           
           $cObject = $this->ffi->create_object($id, $cName);
           
           return new ExternalObject($this->ffi, $cObject);
       }
   }
   
   class ExternalObject
   {
       private \FFI $ffi;
       private \FFI\CData $cObject;
       
       public function __construct(\FFI $ffi, \FFI\CData $cObject)
       {
           $this->ffi = $ffi;
           $this->cObject = $cObject;
       }
       
       public function getId(): int
       {
           return $this->ffi->get_object_id($this->cObject);
       }
       
       public function __destruct()
       {
           $this->ffi->destroy_object($this->cObject);
       }
   }
   ```

5. **Memory Management**
   ```c
   // Custom memory pool
   typedef struct {
       void *memory;
       size_t size;
       size_t used;
       struct memory_pool *next;
   } memory_pool;
   
   static memory_pool *pool_head = NULL;
   
   // Allocate from pool
   void* pool_alloc(size_t size)
   {
       if (!pool_head || (pool_head->used + size) > pool_head->size) {
           // Create new pool
           memory_pool *new_pool = pemalloc(POOL_SIZE, 1);
           new_pool->memory = pemalloc(POOL_SIZE - sizeof(memory_pool), 1);
           new_pool->size = POOL_SIZE - sizeof(memory_pool);
           new_pool->used = 0;
           new_pool->next = pool_head;
           pool_head = new_pool;
       }
       
       void *ptr = (char*)pool_head->memory + pool_head->used;
       pool_head->used += size;
       
       return ptr;
   }
   
   // Cleanup pools
   PHP_RSHUTDOWN_FUNCTION(my_extension)
   {
       while (pool_head) {
           memory_pool *next = pool_head->next;
           pefree(pool_head->memory, 1);
           pefree(pool_head, 1);
           pool_head = next;
       }
       
       return SUCCESS;
   }
   ```

### Frontend Interface
The React frontend should:
1. Demonstrate extension functionality
2. Show performance comparisons
3. Visualize thread safety testing
4. Provide educational content about extension development
5. Display system information and extension status

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of thread-safe operations
   - Correct memory management
   - Accurate external library integration

2. **Advanced Features** (25%)
   - Custom object handlers implementation
   - Complex resource management
   - FFI integration quality

3. **Thread Safety** (20%)
   - Proper locking mechanisms
   - Concurrent access handling
   - Resource sharing safety

4. **Code Quality** (15%)
   - Clean, well-organized C code
   - Proper error handling
   - Comprehensive documentation

5. **Educational Value** (10%)
   - Clear explanations of advanced concepts
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [PHP Extension Development Documentation](https://www.php.net/manual/en/internals2.php)
2. [Zend Engine API Reference](https://www.php.net/manual/en/internals2.structure.php)
3. [PHP FFI Documentation](https://www.php.net/manual/en/book.ffi.php)
4. [Writing PHP Extensions](https://www.sitepoint.com/writing-php-extensions/)
5. [Thread Safety in PHP Extensions](https://www.php.net/manual/en/internals2.memory.tsrm.php)
6. [PHP Memory Management](https://www.php.net/manual/en/internals2.memory.php)
7. [PHP Object Handlers](https://www.php.net/manual/en/internals2.objects.php)

## Stretch Goals
1. Implement a garbage collector for custom objects
2. Create bindings for a complex C++ library
3. Build a thread-safe connection pool
4. Implement custom stream wrappers
5. Create a JIT compiler for a domain-specific language
6. Develop debugging tools for extension development
7. Implement advanced profiling capabilities