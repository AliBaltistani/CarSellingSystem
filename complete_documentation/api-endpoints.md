# API Endpoints Documentation
## Car Selling Web App - Laravel API Routes

---

## Public Routes (No Authentication Required)

### Homepage & General
```
GET  /                          - Homepage
GET  /about                     - About page
GET  /contact                   - Contact page
POST /contact                   - Submit contact form
GET  /privacy-policy            - Privacy policy
GET  /terms-of-service          - Terms of service
```

### Cars
```
GET  /cars                      - List all cars (with filters)
GET  /cars/{slug}               - Show single car detail
POST /cars/{id}/view            - Increment view count
GET  /cars/search               - Search cars (Ajax)
GET  /api/cars/nearby           - Get cars near location (Ajax)

Query Parameters for /cars:
- make              (string)  Filter by make
- model             (string)  Filter by model
- min_price         (number)  Minimum price
- max_price         (number)  Maximum price
- min_year          (number)  Minimum year
- max_year          (number)  Maximum year
- condition         (string)  new|used|certified
- transmission      (string)  automatic|manual
- fuel_type         (string)  petrol|diesel|electric|hybrid
- body_type         (string)  sedan|suv|etc
- category          (string)  Category slug
- lat               (float)   User latitude
- lng               (float)   User longitude
- radius            (number)  Search radius in km (default: 50)
- sort              (string)  price_asc|price_desc|date_desc|distance
- per_page          (number)  Results per page (default: 20)
```

### Categories
```
GET  /categories                - List all categories
GET  /categories/{slug}         - Show category with cars
```

### Location API
```
POST /api/location/detect       - Save user location
POST /api/location/reverse      - Reverse geocode coordinates
GET  /api/location/cities       - Get available cities

Request Body (POST /api/location/detect):
{
  "latitude": 25.2048,
  "longitude": 55.2708,
  "city": "Dubai",
  "country": "UAE"
}
```

---

## Authenticated User Routes

### Authentication
```
GET  /login                     - Login form
POST /login                     - Login submit
POST /logout                    - Logout
GET  /register                  - Registration form
POST /register                  - Registration submit
GET  /forgot-password           - Forgot password form
POST /forgot-password           - Send reset link
GET  /reset-password/{token}    - Reset password form
POST /reset-password            - Reset password submit
GET  /verify-email/{id}/{hash}  - Email verification
POST /email/verification-notification - Resend verification
```

### User Dashboard
```
GET  /dashboard                 - User dashboard
GET  /profile                   - View profile
PUT  /profile                   - Update profile
GET  /profile/edit              - Edit profile form
```

### User Cars
```
GET  /my-cars                   - List user's cars
GET  /my-cars/create            - Create car form
POST /my-cars                   - Store new car
GET  /my-cars/{id}/edit         - Edit car form
PUT  /my-cars/{id}              - Update car
DELETE /my-cars/{id}            - Delete car
PATCH /my-cars/{id}/status      - Update car status
POST /my-cars/{id}/images       - Upload car images
DELETE /my-cars/{id}/images/{imageId} - Delete image
```

### Favorites
```
GET  /favorites                 - List favorite cars
POST /favorites/{carId}         - Add to favorites
DELETE /favorites/{carId}       - Remove from favorites
GET  /api/favorites/check/{carId} - Check if favorited
```

### Saved Searches
```
GET  /saved-searches            - List saved searches
POST /saved-searches            - Save new search
DELETE /saved-searches/{id}     - Delete saved search
```

### Inquiries
```
GET  /inquiries                 - List user's inquiries
POST /inquiries                 - Submit inquiry
GET  /my-cars/{id}/inquiries    - Inquiries for user's car

Request Body (POST /inquiries):
{
  "car_id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+971501234567",
  "message": "I'm interested in this car"
}
```

---

## Admin Routes (Requires Admin Role)

### Admin Dashboard
```
GET  /admin                     - Admin dashboard
GET  /admin/analytics           - Analytics page
GET  /admin/settings            - Settings page
POST /admin/settings            - Update settings
```

### Admin Cars Management
```
GET  /admin/cars                - List all cars
GET  /admin/cars/create         - Create car form
POST /admin/cars                - Store new car
GET  /admin/cars/{id}           - View car details
GET  /admin/cars/{id}/edit      - Edit car form
PUT  /admin/cars/{id}           - Update car
DELETE /admin/cars/{id}         - Delete car
POST /admin/cars/bulk-action    - Bulk actions (publish/delete/feature)
PATCH /admin/cars/{id}/feature  - Toggle featured status
PATCH /admin/cars/{id}/publish  - Toggle publish status

Request Body (POST /admin/cars/bulk-action):
{
  "action": "publish|unpublish|delete|feature|unfeature",
  "car_ids": [1, 2, 3]
}
```

### Admin Categories
```
GET  /admin/categories          - List categories
GET  /admin/categories/create   - Create category form
POST /admin/categories          - Store category
GET  /admin/categories/{id}/edit - Edit category form
PUT  /admin/categories/{id}     - Update category
DELETE /admin/categories/{id}   - Delete category
POST /admin/categories/reorder  - Reorder categories
```

### Admin Users
```
GET  /admin/users               - List all users
GET  /admin/users/create        - Create user form
POST /admin/users               - Store user
GET  /admin/users/{id}          - View user details
GET  /admin/users/{id}/edit     - Edit user form
PUT  /admin/users/{id}          - Update user
DELETE /admin/users/{id}        - Delete user
PATCH /admin/users/{id}/ban     - Ban/unban user
PATCH /admin/users/{id}/role    - Change user role
```

### Admin Inquiries
```
GET  /admin/inquiries           - List all inquiries
GET  /admin/inquiries/{id}      - View inquiry
PATCH /admin/inquiries/{id}/status - Update inquiry status
DELETE /admin/inquiries/{id}    - Delete inquiry
```

### Admin Reports
```
GET  /admin/reports/cars        - Cars report
GET  /admin/reports/users       - Users report
GET  /admin/reports/revenue     - Revenue report
GET  /admin/reports/locations   - Cars by location
GET  /admin/reports/export      - Export reports (CSV/PDF)
```

### Admin Media
```
GET  /admin/media               - Media library
POST /admin/media/upload        - Upload media
DELETE /admin/media/{id}        - Delete media
```

---

## API Routes (JSON Responses)

### Public API
```
GET  /api/v1/cars               - List cars (JSON)
GET  /api/v1/cars/{id}          - Get car details (JSON)
GET  /api/v1/categories         - List categories (JSON)
GET  /api/v1/makes              - Get all makes
GET  /api/v1/models/{make}      - Get models for make
GET  /api/v1/stats              - Get site statistics

Response (GET /api/v1/stats):
{
  "total_cars": 200,
  "active_listings": 150,
  "sold_cars": 50,
  "categories": 10
}
```

### Location API
```
POST /api/v1/location/nearby    - Find nearby cars
GET  /api/v1/location/cities    - Available cities
GET  /api/v1/location/countries - Available countries

Request (POST /api/v1/location/nearby):
{
  "latitude": 25.2048,
  "longitude": 55.2708,
  "radius": 50,
  "filters": {
    "min_price": 50000,
    "max_price": 200000
  }
}

Response:
{
  "data": [
    {
      "id": 1,
      "title": "2020 BMW X5",
      "distance": 2.5,
      "price": 150000,
      "image": "url"
    }
  ],
  "meta": {
    "total": 10,
    "radius": 50
  }
}
```

### Search API
```
GET  /api/v1/search             - Global search
GET  /api/v1/search/autocomplete - Search autocomplete

Response (GET /api/v1/search/autocomplete?q=bmw):
{
  "makes": ["BMW"],
  "models": ["X5", "X3", "530i"],
  "cars": [
    {
      "id": 1,
      "title": "2020 BMW X5",
      "slug": "2020-bmw-x5"
    }
  ]
}
```

---

## Route Naming Conventions

All routes follow Laravel naming conventions:
```
cars.index       - GET /cars
cars.show        - GET /cars/{slug}
cars.create      - GET /cars/create
cars.store       - POST /cars
cars.edit        - GET /cars/{id}/edit
cars.update      - PUT/PATCH /cars/{id}
cars.destroy     - DELETE /cars/{id}
```

---

## Middleware Stack

### Public Routes
- `web` - Web middleware group (sessions, CSRF, cookies)
- `throttle:60,1` - Rate limiting (60 requests per minute)

### Authenticated Routes
- `web`
- `auth` - Require authentication
- `verified` - Require email verification (optional)

### Admin Routes
- `web`
- `auth`
- `role:admin` - Require admin role
- `throttle:120,1` - Higher rate limit for admins

### API Routes
- `api` - API middleware group
- `throttle:api` - API rate limiting (default 60/min)
- `auth:sanctum` - For authenticated API routes

---

## Rate Limiting

```php
// config/api.php rate limits
'public' => 60,         // requests per minute
'auth' => 120,          // authenticated users
'admin' => 300,         // admin users
'search' => 30,         // search endpoints
'location' => 100,      // location detection
```

---

## Response Formats

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error"]
  }
}
```

### Pagination Response
```json
{
  "data": [...],
  "links": {
    "first": "url",
    "last": "url",
    "prev": null,
    "next": "url"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 100
  }
}
```

---

## WhatsApp Integration

Direct WhatsApp links from car detail page:
```
https://wa.me/971501234567?text=Hi%2C%20I'm%20interested%20in%20{car_title}
```

Click-to-call:
```
tel:+971501234567
```

---

## Security Headers

All responses include:
```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
Content-Security-Policy: default-src 'self'
```

---

## CORS Configuration

For API routes:
```php
'paths' => ['api/*'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_origins' => ['*'],
'allowed_headers' => ['*'],
'max_age' => 86400,
```
