# ProsightTask Laravel API

A Laravel application implementing a Salesmen API with PostgreSQL database, following enterprise-level architecture patterns with strict type checking (PHPStan Level 10).

## Features

- **Enterprise Architecture**: DTOs, Repositories, Services, Enums, Custom Exceptions
- **Strict Type Checking**: PHPStan Level 10 with Larastan
- **API Resources**: Proper response formatting with OpenAPI specification compliance
- **Database Seeding**: Automatic population from CSV file
- **Docker Support**: Complete containerization with Apache and PostgreSQL
- **Automated Setup**: All Laravel commands run on container startup

## API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

### Salesmen Operations

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/salesmen` | List all salesmen with pagination and filters |
| `POST` | `/salesmen` | Create a new salesman |
| `GET` | `/salesmen/{id}` | Get a specific salesman |
| `PUT` | `/salesmen/{id}` | Update a salesman |
| `DELETE` | `/salesmen/{id}` | Delete a salesman |

### Codelists

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/codelists` | Get all available codelists (genders, marital status, titles) |

### Health Check

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/health` | Application health status |

## API Examples

### 1. Create Salesman (POST)

**Request:**
```bash
curl -X POST http://localhost:8000/api/v1/salesmen \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "titles_before": ["Dr."],
    "titles_after": ["PhD."],
    "prosight_id": "PROS001",
    "email": "john.doe@example.com",
    "phone": "+421 123 456 789",
    "gender": "m",
    "marital_status": "married"
  }'
```

**Response (201 Created):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "self": "/api/v1/salesmen/550e8400-e29b-41d4-a716-446655440000",
    "first_name": "John",
    "last_name": "Doe",
    "display_name": "Dr. John Doe PhD.",
    "titles_before": ["Dr."],
    "titles_after": ["PhD."],
    "prosight_id": "PROS001",
    "email": "john.doe@example.com",
    "phone": "+421 123 456 789",
    "gender": "m",
    "marital_status": "married",
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 2. Update Salesman (PUT)

**Request:**
```bash
curl -X PUT http://localhost:8000/api/v1/salesmen/550e8400-e29b-41d4-a716-446655440000 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Smith",
    "titles_before": ["Mgr."],
    "titles_after": null,
    "prosight_id": "PROS001",
    "email": "jane.smith@example.com",
    "phone": "+421 987 654 321",
    "gender": "f",
    "marital_status": "single"
  }'
```

**Response (200 OK):**
```json
{
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "self": "/api/v1/salesmen/550e8400-e29b-41d4-a716-446655440000",
    "first_name": "Jane",
    "last_name": "Smith",
    "display_name": "Mgr. Jane Smith",
    "titles_before": ["Mgr."],
    "titles_after": null,
    "prosight_id": "PROS001",
    "email": "jane.smith@example.com",
    "phone": "+421 987 654 321",
    "gender": "f",
    "marital_status": "single",
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T11:45:00.000000Z"
  }
}
```

### 3. List Salesmen (GET)

**Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/salesmen?page=1&per_page=10&sort=-created_at&gender=m&marital_status=married" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "self": "/api/v1/salesmen/550e8400-e29b-41d4-a716-446655440000",
      "first_name": "John",
      "last_name": "Doe",
      "display_name": "Dr. John Doe PhD.",
      "titles_before": ["Dr."],
      "titles_after": ["PhD."],
      "prosight_id": "PROS001",
      "email": "john.doe@example.com",
      "phone": "+421 123 456 789",
      "gender": "m",
      "marital_status": "married",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "/api/v1/salesmen?page=1",
    "last": "/api/v1/salesmen?page=5",
    "prev": null,
    "next": "/api/v1/salesmen?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```

### 4. Get Codelists (GET)

**Request:**
```bash
curl -X GET http://localhost:8000/api/v1/codelists \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
  "genders": [
    {
      "code": "m",
      "description": "Male"
    },
    {
      "code": "f",
      "description": "Female"
    }
  ],
  "marital_statuses": [
    {
      "code": "single",
      "description": "Single"
    },
    {
      "code": "married",
      "description": "Married"
    },
    {
      "code": "divorced",
      "description": "Divorced"
    },
    {
      "code": "widowed",
      "description": "Widowed"
    }
  ],
  "titles_before": [
    "arch.",
    "Bc.",
    "dipl.",
    "doc.",
    "Dr.",
    "Ing.",
    "JUDr.",
    "MDDr.",
    "Mgr.",
    "Mgr. art.",
    "MUDr.",
    "MVDr.",
    "PaedDr.",
    "PharmDr.",
    "PhDr.",
    "PhMr.",
    "prof.",
    "RNDr.",
    "RSDr.",
    "ThDr.",
    "ThLic."
  ],
  "titles_after": [
    "ArtD.",
    "BSBA",
    "CSc.",
    "DBA",
    "DiS",
    "DiS.art",
    "DrSc.",
    "FCCA",
    "FEBO",
    "FEBU",
    "LL.M",
    "MBA",
    "MHA",
    "MPH",
    "MSc.",
    "PhD."
  ]
}
```

## Docker Setup

### Structure

```
docker/
├── Dockerfile          # PHP 8.3 + Apache container
├── start.sh           # Startup script with Laravel commands
├── postgres/          # PostgreSQL data
│   └── data/
└── README.md          # This file
```

### Quick Start

1. **Start containers:**
```bash
docker compose up -d --build
```

**All settings are automatically applied:**
- ✅ Environment variables configured in Dockerfile
- ✅ Migrations run automatically
- ✅ Seeders run automatically (populated from salesmen.csv)
- ✅ Laravel cache optimized
- ✅ Composer dependencies installed
- ✅ PHPStan analysis executed
- ✅ File permissions set correctly

### Services

- **App**: http://localhost:8000 (Laravel application)
- **Database**: localhost:5432 (PostgreSQL)
- **phpMyAdmin**: http://localhost:8080

### Useful Commands

```bash
# View logs
docker compose logs -f app

# Execute commands in container
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php ./vendor/bin/phpstan analyse

# Stop containers
docker compose down

# Rebuild containers
docker compose up
```

## Technology Stack

- **PHP**: 8.3
- **Laravel**: 12.x
- **Database**: PostgreSQL 15
- **Web Server**: Apache
- **Type Checking**: PHPStan Level 10 with Larastan
- **Container**: Docker & Docker Compose
- **Caching**: File-based (no Redis)

## Architecture

- **Models**: Eloquent with strict typing
- **Controllers**: API controllers with proper HTTP responses
- **Services**: Business logic layer
- **Repositories**: Data access layer
- **DTOs**: Data Transfer Objects with validation
- **Enums**: Type-safe constants for codelists
- **Resources**: API response formatting
- **Requests**: Form request validation
- **Exceptions**: Custom exception handling

## Development

### Running Tests
```bash
docker compose exec app php artisan test
```

### Code Quality
```bash
docker compose exec app php ./vendor/bin/phpstan analyse
```

### Database Seeding
The application automatically seeds the database with data from `database/seeders/salesmen.csv` on startup.

For starting of project  execute: docker compose up
"# prosighttask" 
