# Challenge 04: File Upload with Security Scanning

## Description
This challenge focuses on implementing a secure file upload system with integrated security scanning capabilities. You'll learn how to handle file uploads safely, validate file types and content, scan for malware or malicious content, and store files securely. The challenge covers security best practices, file validation techniques, and creating a robust file management system.

## Learning Objectives
- Implement secure file upload mechanisms
- Validate file types, sizes, and content
- Integrate security scanning for malware detection
- Store files securely with proper access controls
- Handle large file uploads efficiently
- Implement file metadata management
- Apply security best practices for file handling

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- ClamAV or similar antivirus software (for scanning)
- Understanding of file security principles
- Knowledge of MIME types and file validation
- Docker (optional, for containerized deployment)

## Features to Implement
1. **File Upload System**
   - Multiple file upload support
   - File type and size validation
   - Content-type verification
   - Upload progress tracking
   - Chunked upload for large files
   
2. **Security Scanning**
   - Virus and malware scanning integration
   - File content analysis
   - Suspicious file detection
   - Quarantine and reporting mechanisms
   - Scan result caching for performance
   
3. **File Management**
   - Secure file storage strategies
   - File metadata tracking
   - Access control and permissions
   - File versioning and history
   - Cleanup and retention policies
   
4. **User Interface**
   - Drag-and-drop upload interface
   - File preview capabilities
   - Upload history and management
   - Error handling and user feedback
   - Responsive design for all devices

## Project Structure
```
challenge-04-file-upload-scan/
├── backend-php/
│   ├── src/
│   │   ├── Upload/
│   │   │   ├── FileUploader.php
│   │   │   ├── FileValidator.php
│   │   │   ├── FileScanner.php
│   │   │   └── UploadService.php
│   │   ├── Storage/
│   │   │   ├── FileManager.php
│   │   │   ├── FileRepository.php
│   │   │   └── StorageAdapter.php
│   │   └── Exception/
│   │       ├── UploadException.php
│   │       ├── ValidationException.php
│   │       └── ScanException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── FileUpload.jsx
│   │   │   ├── FileList.jsx
│   │   │   ├── FilePreview.jsx
│   │   │   └── ScanResults.jsx
│   │   ├── services/
│   │   │   └── fileService.js
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- Docker and Docker Compose (recommended)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup (Recommended)
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints
- `POST /api/upload` - Upload file with security scanning
- `GET /api/files` - List uploaded files
- `GET /api/files/{id}` - Get file information
- `GET /api/files/{id}/download` - Download file
- `DELETE /api/files/{id}` - Delete file
- `GET /api/scan/{id}` - Get scan results for file

## Evaluation Criteria
- [ ] Secure file upload implementation
- [ ] Effective file validation and type checking
- [ ] Integration with security scanning tools
- [ ] Proper error handling and user feedback
- [ ] Efficient file storage and management
- [ ] Code quality and documentation
- [ ] Test coverage for core functionality

## Resources
- [OWASP File Upload Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)
- [ClamAV Documentation](https://www.clamav.net/documents.html)
- [PHP File Upload Security](https://www.php.net/manual/en/features.file-upload.php)
- [MIME Type Detection](https://www.php.net/manual/en/function.mime-content-type.php)