# PHP Quotes API

## Author
Javaughn Wells

## Project Home Page
[Live Project Home Page] https://php-midterm-lrh8.onrender.com

## Description
This project is a RESTful Quotes API built with PHP. It supports CRUD operations for authors, categories, and quotes.

## Database
This project uses PostgreSQL as its database.

## Features
- Create, read, update, and delete authors
- Create, read, update, and delete categories
- Create, read, update, and delete quotes
- Filter quotes by author
- Filter quotes by category
- Filter quotes by author and category
- JSON responses for all endpoints

## Endpoints

### Authors
- `GET /api/authors/`
- `GET /api/authors/?id=1`
- `POST /api/authors/`
- `PUT /api/authors/`
- `DELETE /api/authors/`

### Categories
- `GET /api/categories/`
- `GET /api/categories/?id=1`
- `POST /api/categories/`
- `PUT /api/categories/`
- `DELETE /api/categories/`

### Quotes
- `GET /api/quotes/`
- `GET /api/quotes/?id=1`
- `GET /api/quotes/?author_id=1`
- `GET /api/quotes/?category_id=1`
- `GET /api/quotes/?author_id=1&category_id=2`
- `POST /api/quotes/`
- `PUT /api/quotes/`
- `DELETE /api/quotes/`
