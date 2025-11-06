# Markdown Previewer

A full-stack markdown previewer application with PHP backend and Next.js frontend.

## Project Structure

```
challenge-08-markdown-previewer/
├── backend-php/
│   ├── app/
│   │   ├── Core/                         # Core application logic (domain + shared)
│   │   │   ├── Contracts/                # Interfaces (ParserInterface, SanitizerInterface…)
│   │   │   ├── DTO/                      # Data Transfer Objects
│   │   │   ├── Exceptions/               # Domain exceptions
│   │   │   ├── Traits/                   # Shared reusable traits
│   │   │   └── Helpers/                  # Common helpers/utilities
│   │   ├── Domain/
│   │   │   └── Markdown/                 # Business rules (pure logic, no I/O)
│   │   │       ├── Entities/
│   │   │       │   ├── MarkdownDocument.php
│   │   │       │   └── SanitizedHtml.php
│   │   │       ├── Services/
│   │   │       │   ├── MarkdownParserService.php
│   │   │       │   ├── HtmlSanitizerService.php
│   │   │       │   └── RenderMarkdownService.php
│   │   │       ├── ValueObjects/
│   │   │       │   ├── MarkdownText.php
│   │   │       │   └── HtmlContent.php
│   │   │       └── Exceptions/
│   │   │           └── InvalidMarkdownException.php
│   │   ├── Application/
│   │   │   ├── UseCases/
│   │   │   │   ├── GeneratePreviewUseCase.php
│   │   │   │   ├── SanitizeContentUseCase.php
│   │   │   │   └── ExportDocumentUseCase.php
│   │   │   └── DTO/
│   │   │       └── PreviewResponseDTO.php
│   │   ├── Infrastructure/
│   │   │   ├── Http/
│   │   │   │   ├── Controllers/
│   │   │   │   │   ├── PreviewController.php
│   │   │   │   │   └── ExportController.php
│   │   │   │   ├── Middleware/
│   │   │   │   │   ├── JsonBodyParserMiddleware.php
│   │   │   │   │   └── CorsMiddleware.php
│   │   │   │   └── Routes/
│   │   │   │       └── api.php
│   │   │   ├── Security/
│   │   │   │   ├── ContentSecurityPolicy.php
│   │   │   │   └── SanitizationRules.php
│   │   │   ├── Export/
│   │   │   │   ├── HtmlExporter.php
│   │   │   │   ├── PdfExporter.php
│   │   │   │   └── ExportFactory.php
│   │   │   └── Services/
│   │   │       ├── FileStorageService.php
│   │   │       └── LoggingService.php
│   │   ├── Config/
│   │   │   ├── app.php
│   │   │   ├── security.php
│   │   │   └── export.php
│   │   └── bootstrap.php                 # Dependency container & route registration
│   ├── public/
│   │   ├── index.php                     # Entry point
│   │   └── .htaccess                     # For Apache rewrite
│   ├── tests/
│   │   ├── Unit/
│   │   ├── Integration/
│   │   └── Feature/
│   ├── composer.json
│   ├── phpunit.xml
│   ├── Dockerfile
│   └── README.md
│
├── frontend-nextjs/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Editor.jsx
│   │   │   ├── Preview.jsx
│   │   │   ├── Toolbar.jsx
│   │   │   ├── SplitPane.jsx
│   │   │   ├── ExportModal.jsx
│   │   │   └── ThemeToggle.jsx
│   │   ├── hooks/
│   │   │   ├── useDebounce.js
│   │   │   ├── useMarkdown.js
│   │   │   └── useTheme.js
│   │   ├── pages/
│   │   │   ├── index.jsx
│   │   │   ├── settings.jsx
│   │   │   └── templates.jsx
│   │   ├── services/
│   │   │   ├── markdownService.js
│   │   │   └── exportService.js
│   │   ├── utils/
│   │   │   ├── sanitizer.js
│   │   │   └── highlight.js
│   │   ├── context/
│   │   │   └── ThemeContext.jsx
│   │   ├── styles/
│   │   │   ├── globals.css
│   │   │   └── themes.css
│   │   ├── tests/
│   │   │   ├── Editor.test.js
│   │   │   ├── Preview.test.js
│   │   │   └── MarkdownService.test.js
│   │   └── App.jsx
│   ├── public/
│   │   └── icons/
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
│
├── docker-compose.yml
├── Makefile
├── .env.example
├── .gitignore
├── .editorconfig
├── .prettierrc
└── README.md
```

## Features

- Real-time markdown preview
- Syntax highlighting
- Theme switching (light/dark mode)
- Export to HTML and PDF
- Responsive design
- Clean architecture with separation of concerns
- Docker containerization for easy deployment

## Getting Started

### Prerequisites

- Docker and Docker Compose
- PHP 8.1+
- Node.js 16+

### Installation

1. Clone the repository
2. Navigate to the project directory
3. Run `make setup` to install dependencies
4. Run `make start` to start the application

### Development

- Backend runs on `http://localhost:8000`
- Frontend runs on `http://localhost:3000`

### Commands

```bash
# Start all services
make start

# Stop all services
make stop

# Run tests
make test

# View logs
make logs
```

## Architecture

This application follows a clean architecture pattern with clear separation of concerns:

1. **Domain Layer**: Contains business logic and entities
2. **Application Layer**: Contains use cases and DTOs
3. **Infrastructure Layer**: Contains controllers, middleware, and external services
4. **Core Layer**: Contains shared contracts, helpers, and exceptions

## License

This project is licensed under the MIT License.