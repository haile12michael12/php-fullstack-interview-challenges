# Challenge 67: Content Negotiation

## Description
In this challenge, you'll implement content negotiation to allow clients to request different representations of the same resource. This enables APIs to serve various formats (JSON, XML, HTML) and languages based on client preferences.

## Learning Objectives
- Understand HTTP content negotiation mechanisms
- Implement server-driven content negotiation
- Handle Accept header parsing
- Create multiple representation formats
- Support language negotiation
- Manage quality values and priorities

## Requirements
Create a content negotiation system with the following features:

1. **Media Type Negotiation**:
   - Parse Accept headers with quality values
   - Match requested media types to available formats
   - Handle wildcard media types (*/*, application/*)
   - Prioritize based on quality values (q-values)
   - Fallback to default representations

2. **Language Negotiation**:
   - Parse Accept-Language headers
   - Match requested languages to available translations
   - Handle language ranges and fallbacks
   - Support regional variants (en-US, en-GB)
   - Default language selection

3. **Content Representation**:
   - Multiple format generators (JSON, XML, HTML)
   - Template-based rendering for HTML
   - Structured data serialization
   - Media type specific formatting
   - Character encoding handling

4. **Advanced Features**:
   - Content negotiation middleware
   - Representation caching
   - Format-specific controllers
   - Custom media types
   - Negotiation logging and analytics

## Features to Implement
- [ ] Accept header parsing and processing
- [ ] Media type matching with quality values
- [ ] Language negotiation support
- [ ] Multiple format generators (JSON, XML, HTML)
- [ ] Template-based HTML rendering
- [ ] Wildcard media type handling
- [ ] Fallback mechanism for unsupported formats
- [ ] Content negotiation middleware
- [ ] Representation caching
- [ ] Custom media type support

## Project Structure
```
backend-php/
├── src/
│   ├── Negotiation/
│   │   ├── ContentNegotiator.php
│   │   ├── MediaTypeNegotiator.php
│   │   ├── LanguageNegotiator.php
│   │   ├── Format/
│   │   │   ├── JsonFormat.php
│   │   │   ├── XmlFormat.php
│   │   │   ├── HtmlFormat.php
│   │   │   └── CsvFormat.php
│   │   └── Middleware/
│   │       └── NegotiationMiddleware.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── AcceptHeader.php
│   ├── Views/
│   │   ├── json/
│   │   ├── xml/
│   │   └── html/
│   └── Controllers/
├── public/
│   └── index.php
├── config/
│   └── negotiation.php
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

## Content Negotiation Examples

### Media Type Negotiation
```
# Client requests JSON
GET /api/users/1
Accept: application/json

# Client requests XML
GET /api/users/1
Accept: application/xml

# Client requests HTML
GET /api/users/1
Accept: text/html

# Client requests multiple formats with priorities
GET /api/users/1
Accept: application/json;q=0.9, application/xml;q=0.8, text/html;q=0.7
```

### Language Negotiation
```
# Client requests English
GET /api/users/1
Accept-Language: en

# Client requests Spanish
GET /api/users/1
Accept-Language: es

# Client requests multiple languages with priorities
GET /api/users/1
Accept-Language: es-ES;q=0.9, fr;q=0.8, en;q=0.7
```

## API Endpoints
```
GET /api/users
GET /api/users/{id}
GET /api/posts
GET /api/posts/{id}
```

## Supported Formats
- `application/json` - JSON representation
- `application/xml` - XML representation
- `text/html` - HTML representation
- `text/csv` - CSV representation
- `application/vnd.api+json` - JSON:API format

## Supported Languages
- `en` - English
- `es` - Spanish
- `fr` - French
- `de` - German
- `ja` - Japanese

## Evaluation Criteria
- [ ] Accept header parsing works correctly
- [ ] Media type matching respects quality values
- [ ] Language negotiation functions properly
- [ ] Multiple formats are generated correctly
- [ ] Wildcard media types are handled
- [ ] Fallback mechanisms work
- [ ] Content negotiation middleware processes requests
- [ ] Representation caching is implemented
- [ ] Code is well-organized and documented
- [ ] Tests cover negotiation functionality

## Resources
- [HTTP Content Negotiation](https://developer.mozilla.org/en-US/docs/Web/HTTP/Content_negotiation)
- [RFC 7231 Section 5.3](https://tools.ietf.org/html/rfc7231#section-5.3)
- [Content Negotiation in REST APIs](https://restfulapi.net/content-negotiation/)
- [Accept Header Specification](https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html)