# Challenge 08: Markdown Previewer

## Description
This challenge focuses on building a real-time Markdown previewer application. You'll create a system that converts Markdown text to HTML in real-time, providing users with an instant preview of their formatted content. The challenge covers text processing, real-time updates, security considerations for user-generated content, and creating a responsive user interface.

## Learning Objectives
- Implement Markdown parsing and conversion to HTML
- Create real-time preview functionality
- Handle user-generated content securely
- Build responsive user interfaces
- Optimize performance for text processing
- Implement syntax highlighting for code blocks
- Apply security best practices for content rendering

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Understanding of Markdown syntax
- Knowledge of HTML sanitization techniques
- Docker (optional, for containerized deployment)

## Features to Implement
1. **Markdown Parser**
   - Support for standard Markdown syntax
   - GitHub Flavored Markdown extensions
   - Code block syntax highlighting
   - Table and list formatting
   
2. **Real-time Preview**
   - Instant preview as users type
   - Debounced processing for performance
   - Responsive layout for editor and preview
   - Keyboard shortcuts and accessibility
   
3. **Security Features**
   - HTML sanitization to prevent XSS
   - Safe rendering of user content
   - Content filtering and validation
   - Secure file upload handling (if implemented)
   
4. **User Interface**
   - Split-pane editor and preview
   - Toolbar with formatting options
   - Export functionality (HTML, PDF)
   - Theme customization options

## Project Structure
```
challenge-08-markdown-previewer/
├── backend-php/
│   ├── src/
│   │   ├── Markdown/
│   │   │   ├── Parser.php
│   │   │   ├── Renderer.php
│   │   │   └── Sanitizer.php
│   │   ├── Service/
│   │   │   └── PreviewService.php
│   │   └── Exception/
│   │       └── ParseException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Editor.jsx
│   │   │   ├── Preview.jsx
│   │   │   ├── Toolbar.jsx
│   │   │   └── MarkdownPreviewer.jsx
│   │   ├── services/
│   │   │   └── markdownService.js
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
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Start the development server with `php public/index.php`

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run `docker-compose up -d`
2. Access the application at `http://localhost:3000`

## API Endpoints
- `POST /api/preview` - Convert Markdown to HTML
- `POST /api/sanitize` - Sanitize HTML content
- `GET /api/templates` - Get available templates
- `POST /api/export` - Export content to various formats

## Evaluation Criteria
- [ ] Accurate Markdown parsing and rendering
- [ ] Real-time preview performance
- [ ] Security measures for user content
- [ ] Responsive and accessible user interface
- [ ] Code quality and documentation
- [ ] Test coverage for core functionality

## Resources
- [Markdown Specification](https://daringfireball.net/projects/markdown/)
- [GitHub Flavored Markdown](https://github.github.com/gfm/)
- [HTML Purifier](http://htmlpurifier.org/)
- [CommonMark PHP](https://commonmark.thephpleague.com/)