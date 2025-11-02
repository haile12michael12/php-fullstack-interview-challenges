# Generate language bindings from proto

Install protoc and grpc plugins.

Example (adjust paths):

# Generate PHP classes (messages)
protoc --proto_path=. --php_out=./generated/php proto/echo.proto

# Generate PHP gRPC service stubs (requires grpc_php_plugin)
protoc --proto_path=. --grpc_out=./generated/php --plugin=protoc-gen-grpc=/path/to/grpc_php_plugin proto/echo.proto

# Generate JS grpc-web client
protoc -I=proto proto/echo.proto \
  --js_out=import_style=commonjs:./generated/js \
  --grpc-web_out=import_style=commonjs,mode=grpcwebtext:./generated/js

