# Challenge 81: Message Serialization

## Description
In this challenge, you'll implement a comprehensive message serialization system that supports multiple formats and protocols. Message serialization is crucial for data exchange between services, ensuring that information can be efficiently transmitted and correctly reconstructed across different systems.

## Learning Objectives
- Understand message serialization concepts and formats
- Implement multiple serialization protocols
- Handle schema evolution and versioning
- Create efficient serialization/deserialization
- Manage binary and text-based formats
- Implement compression and encryption

## Requirements
Create a message serialization system with the following features:

1. **Serialization Formats**:
   - JSON serialization
   - XML serialization
   - Binary serialization (MessagePack, Protocol Buffers)
   - CSV serialization
   - YAML serialization
   - Custom format support

2. **Schema Management**:
   - Schema definition and validation
   - Schema versioning
   - Schema evolution
   - Backward/forward compatibility
   - Schema registry integration
   - Schema migration

3. **Performance Optimization**:
   - Efficient serialization algorithms
   - Memory usage optimization
   - Streaming serialization
   - Compression support
   - Caching mechanisms
   - Batch processing

4. **Advanced Features**:
   - Encryption and security
   - Error handling and recovery
   - Metadata support
   - Type mapping
   - Validation hooks
   - Plugin architecture

## Features to Implement
- [ ] JSON serialization
- [ ] XML serialization
- [ ] Binary serialization (MessagePack)
- [ ] Protocol Buffers support
- [ ] CSV serialization
- [ ] YAML serialization
- [ ] Schema definition and validation
- [ ] Schema versioning
- [ ] Schema evolution
- [ ] Compression support
- [ ] Encryption and security
- [ ] Streaming serialization
- [ ] Error handling

## Project Structure
```
backend-php/
├── src/
│   ├── Serialization/
│   │   ├── Serializer.php
│   │   ├── Formats/
│   │   │   ├── JsonSerializer.php
│   │   │   ├── XmlSerializer.php
│   │   │   ├── BinarySerializer.php
│   │   │   ├── CsvSerializer.php
│   │   │   ├── YamlSerializer.php
│   │   │   └── ProtobufSerializer.php
│   │   ├── Schema/
│   │   │   ├── SchemaManager.php
│   │   │   ├── SchemaValidator.php
│   │   │   ├── SchemaVersion.php
│   │   │   └── SchemaRegistry.php
│   │   ├── Compression/
│   │   │   ├── Compressor.php
│   │   │   ├── GzipCompressor.php
│   │   │   └── SnappyCompressor.php
│   │   ├── Security/
│   │   │   ├── EncryptionManager.php
│   │   │   └── SignatureManager.php
│   │   └── Streaming/
│   │       ├── StreamWriter.php
│   │       └── StreamReader.php
│   ├── Messages/
│   │   ├── Message.php
│   │   ├── UserMessage.php
│   │   ├── OrderMessage.php
│   │   └── NotificationMessage.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── schemas/
├── config/
│   └── serialization.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── serialization.js
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

## Serialization Formats

### JSON Serialization
Standard JSON serialization with custom options:
```php
class JsonSerializer implements SerializerInterface
{
    private $options;
    
    public function __construct($options = [])
    {
        $this->options = array_merge([
            'pretty_print' => false,
            'preserve_keys' => true,
            'escape_unicode' => true
        ], $options);
    }
    
    public function serialize($data)
    {
        $flags = 0;
        if ($this->options['pretty_print']) {
            $flags |= JSON_PRETTY_PRINT;
        }
        if (!$this->options['escape_unicode']) {
            $flags |= JSON_UNESCAPED_UNICODE;
        }
        
        return json_encode($data, $flags);
    }
    
    public function deserialize($data, $type = null)
    {
        $result = json_decode($data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SerializationException('Invalid JSON: ' . json_last_error_msg());
        }
        
        if ($type && class_exists($type)) {
            return $this->mapToObject($result, $type);
        }
        
        return $result;
    }
    
    private function mapToObject($data, $class)
    {
        $reflection = new ReflectionClass($class);
        $instance = $reflection->newInstanceWithoutConstructor();
        
        foreach ($data as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($instance, $value);
            }
        }
        
        return $instance;
    }
}
```

### Binary Serialization (MessagePack)
Efficient binary serialization:
```php
class BinarySerializer implements SerializerInterface
{
    public function serialize($data)
    {
        if (!extension_loaded('msgpack')) {
            throw new RuntimeException('MessagePack extension not available');
        }
        
        return msgpack_pack($data);
    }
    
    public function deserialize($data, $type = null)
    {
        if (!extension_loaded('msgpack')) {
            throw new RuntimeException('MessagePack extension not available');
        }
        
        $result = msgpack_unpack($data);
        
        if ($type && class_exists($type)) {
            return $this->mapToObject($result, $type);
        }
        
        return $result;
    }
}
```

### Protocol Buffers Serialization
High-performance serialization with schema support:
```php
class ProtobufSerializer implements SerializerInterface
{
    private $schemaRegistry;
    
    public function __construct(SchemaRegistry $schemaRegistry)
    {
        $this->schemaRegistry = $schemaRegistry;
    }
    
    public function serialize($data, $schemaName = null)
    {
        if (!$schemaName) {
            throw new InvalidArgumentException('Schema name required for Protocol Buffers');
        }
        
        $schema = $this->schemaRegistry->getSchema($schemaName);
        $message = $this->createProtobufMessage($data, $schema);
        
        return $message->serializeToString();
    }
    
    public function deserialize($data, $schemaName = null)
    {
        if (!$schemaName) {
            throw new InvalidArgumentException('Schema name required for Protocol Buffers');
        }
        
        $schema = $this->schemaRegistry->getSchema($schemaName);
        $messageClass = $schema->getMessageClass();
        $message = new $messageClass();
        $message->mergeFromString($data);
        
        return $this->convertToAssociativeArray($message);
    }
    
    private function createProtobufMessage($data, $schema)
    {
        $messageClass = $schema->getMessageClass();
        $message = new $messageClass();
        
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($message, $setter)) {
                $message->$setter($value);
            }
        }
        
        return $message;
    }
}
```

## Schema Management
```php
class SchemaManager
{
    private $schemas = [];
    private $registry;
    
    public function registerSchema($name, $schema)
    {
        $this->schemas[$name] = $schema;
        $this->registry->register($name, $schema);
    }
    
    public function getSchema($name, $version = null)
    {
        if ($version) {
            return $this->registry->getVersion($name, $version);
        }
        
        return $this->schemas[$name] ?? null;
    }
    
    public function validate($data, $schemaName)
    {
        $schema = $this->getSchema($schemaName);
        return $schema->validate($data);
    }
    
    public function migrate($data, $fromSchema, $toSchema)
    {
        // Handle schema evolution
        return $this->schemaMigrator->migrate($data, $fromSchema, $toSchema);
    }
}

class Schema
{
    private $name;
    private $version;
    private $fields;
    private $rules;
    
    public function __construct($name, $version, $definition)
    {
        $this->name = $name;
        $this->version = $version;
        $this->fields = $definition['fields'] ?? [];
        $this->rules = $definition['rules'] ?? [];
    }
    
    public function validate($data)
    {
        $validator = new SchemaValidator($this->rules);
        return $validator->validate($data);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getVersion()
    {
        return $this->version;
    }
    
    public function getFields()
    {
        return $this->fields;
    }
}
```

## Configuration Example
```php
[
    'default_format' => 'json',
    'formats' => [
        'json' => [
            'class' => JsonSerializer::class,
            'options' => [
                'pretty_print' => false,
                'escape_unicode' => true
            ]
        ],
        'binary' => [
            'class' => BinarySerializer::class,
            'options' => [
                'compression' => 'gzip'
            ]
        ],
        'protobuf' => [
            'class' => ProtobufSerializer::class,
            'schema_path' => '/schemas/protobuf',
            'auto_compile' => true
        ],
        'xml' => [
            'class' => XmlSerializer::class,
            'options' => [
                'root_element' => 'data',
                'format_output' => true
            ]
        ]
    ],
    'schemas' => [
        'user' => [
            'versions' => [
                '1.0' => [
                    'fields' => [
                        'id' => 'integer',
                        'name' => 'string',
                        'email' => 'string'
                    ]
                ],
                '2.0' => [
                    'fields' => [
                        'id' => 'integer',
                        'name' => 'string',
                        'email' => 'string',
                        'created_at' => 'datetime',
                        'updated_at' => 'datetime'
                    ],
                    'migration_from' => '1.0'
                ]
            ]
        ]
    ],
    'compression' => [
        'enabled' => true,
        'algorithm' => 'gzip',
        'threshold' => 1024, // Compress messages larger than 1KB
        'level' => 6
    ],
    'security' => [
        'encryption' => false,
        'signature' => true,
        'algorithm' => 'sha256'
    ]
]
```

## Message Serialization Service
```php
class SerializationService
{
    private $serializers = [];
    private $schemaManager;
    private $compressor;
    private $encryptionManager;
    
    public function serialize($data, $format = null, $schema = null)
    {
        $format = $format ?: config('serialization.default_format');
        $serializer = $this->getSerializer($format);
        
        // Serialize the data
        $serialized = $serializer->serialize($data, $schema);
        
        // Add metadata
        $metadata = [
            'format' => $format,
            'schema' => $schema,
            'timestamp' => time(),
            'checksum' => md5($serialized)
        ];
        
        // Compress if needed
        if ($this->shouldCompress($serialized)) {
            $serialized = $this->compressor->compress($serialized);
            $metadata['compressed'] = true;
        }
        
        // Encrypt if needed
        if ($this->shouldEncrypt()) {
            $serialized = $this->encryptionManager->encrypt($serialized);
            $metadata['encrypted'] = true;
        }
        
        // Add metadata to the message
        return $this->packWithMetadata($serialized, $metadata);
    }
    
    public function deserialize($packedData)
    {
        // Extract metadata
        $data = $this->unpackWithMetadata($packedData);
        $metadata = $data['metadata'];
        $payload = $data['payload'];
        
        // Decrypt if needed
        if (isset($metadata['encrypted']) && $metadata['encrypted']) {
            $payload = $this->encryptionManager->decrypt($payload);
        }
        
        // Decompress if needed
        if (isset($metadata['compressed']) && $metadata['compressed']) {
            $payload = $this->compressor->decompress($payload);
        }
        
        // Deserialize the payload
        $serializer = $this->getSerializer($metadata['format']);
        return $serializer->deserialize($payload, $metadata['schema'] ?? null);
    }
    
    private function shouldCompress($data)
    {
        $config = config('serialization.compression');
        return $config['enabled'] && strlen($data) > $config['threshold'];
    }
    
    private function shouldEncrypt()
    {
        return config('serialization.security.encryption');
    }
}
```

## API Endpoints
```
# Message Serialization Management
POST   /serialization/serialize        # Serialize data
POST   /serialization/deserialize      # Deserialize data
GET    /serialization/formats         # List available formats
GET    /serialization/schemas         # List available schemas
POST   /serialization/schemas         # Register new schema
GET    /serialization/schemas/{name}  # Get schema details
POST   /serialization/validate         # Validate data against schema
GET    /serialization/stats           # Get serialization statistics
POST   /serialization/batch           # Batch serialize/deserialize
GET    /serialization/config          # Get serialization configuration
```

## Serialization Statistics Response
```json
{
  "formats": {
    "json": {
      "total_serializations": 15420,
      "total_deserializations": 14250,
      "average_size": "2.4KB",
      "compression_rate": 0.65,
      "performance": {
        "serialize_time": "0.5ms",
        "deserialize_time": "0.8ms"
      }
    },
    "binary": {
      "total_serializations": 8420,
      "total_deserializations": 7890,
      "average_size": "1.2KB",
      "compression_rate": 0.45,
      "performance": {
        "serialize_time": "0.3ms",
        "deserialize_time": "0.4ms"
      }
    },
    "protobuf": {
      "total_serializations": 4250,
      "total_deserializations": 3980,
      "average_size": "0.8KB",
      "compression_rate": 0.35,
      "performance": {
        "serialize_time": "0.2ms",
        "deserialize_time": "0.3ms"
      }
    }
  },
  "schemas": {
    "total_registered": 15,
    "most_used": [
      {"name": "user", "version": "2.0", "usage": 12450},
      {"name": "order", "version": "1.5", "usage": 8420},
      {"name": "notification", "version": "1.0", "usage": 4250}
    ]
  },
  "performance": {
    "total_operations": 42340,
    "average_time": "0.4ms",
    "peak_throughput": "15000 ops/sec",
    "error_rate": 0.001
  }
}
```

## Schema Definition Example
```json
{
  "name": "user",
  "version": "2.0",
  "description": "User information schema",
  "fields": {
    "id": {
      "type": "integer",
      "required": true,
      "description": "Unique user identifier"
    },
    "name": {
      "type": "string",
      "required": true,
      "min_length": 1,
      "max_length": 100,
      "description": "User's full name"
    },
    "email": {
      "type": "string",
      "required": true,
      "format": "email",
      "description": "User's email address"
    },
    "created_at": {
      "type": "datetime",
      "required": false,
      "description": "Account creation timestamp"
    },
    "updated_at": {
      "type": "datetime",
      "required": false,
      "description": "Last update timestamp"
    }
  },
  "indexes": [
    {"fields": ["email"], "unique": true},
    {"fields": ["created_at"]}
  ]
}
```

## Evaluation Criteria
- [ ] JSON serialization works correctly
- [ ] XML serialization handles complex data
- [ ] Binary serialization (MessagePack) is efficient
- [ ] Protocol Buffers support schema validation
- [ ] CSV serialization handles tabular data
- [ ] YAML serialization supports complex structures
- [ ] Schema definition and validation function
- [ ] Schema versioning manages evolution
- [ ] Schema migration handles changes
- [ ] Compression reduces message size
- [ ] Encryption secures sensitive data
- [ ] Streaming serialization handles large data
- [ ] Error handling manages failures
- [ ] Code is well-organized and documented
- [ ] Tests cover serialization functionality

## Resources
- [Serialization Patterns](https://martinfowler.com/eaaCatalog/serializer.html)
- [Protocol Buffers](https://developers.google.com/protocol-buffers)
- [MessagePack](https://msgpack.org/)
- [Apache Avro](https://avro.apache.org/)
- [JSON Schema](https://json-schema.org/)