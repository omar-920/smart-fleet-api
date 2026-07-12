# 🚚 Smart Fleet API

A robust, high-performance fleet management and delivery tracking backend system built with Laravel. This API handles the complete lifecycle of delivery orders, real-time driver tracking, and dynamic pricing using spatial data and routing engines.

## ✨ Key Features

*   **🔒 Secure Authentication & OTP:** Multi-guard authentication for Shops and Drivers using Laravel Sanctum, reinforced with OTP verification.
*   **📍 Real-Time Spatial Tracking:** Utilizes **Redis Geo-Radius** to instantly track driver locations and efficiently filter available drivers within a 5km radius of the pickup point.
*   **🗺️ Dynamic Routing & Pricing:** Integrated with **OSRM (Open Source Routing Machine)** to calculate precise distances and generate dynamic order pricing based on real routes.
*   **📦 Order Lifecycle Management:** Complete state machine for orders (Pending, Accepted, Delivered) with secure proof-of-delivery uploads.
*   **🐳 Dockerized Environment:** Fully containerized development environment using Laravel Sail (Docker + WSL).

## 🛠️ Tech Stack

*   **Framework:** Laravel 11 (PHP 8.x)
*   **Database:** MySQL
*   **In-Memory Store:** Redis (for queue management and spatial data)
*   **Routing Engine:** OSRM (Open Source Routing Machine)
*   **Containerization:** Docker & Laravel Sail
*   **API Documentation:** Knuckles Scribe & Postman

## 🚀 Getting Started

To get this project up and running on your local machine, follow these steps:

### Prerequisites
Make sure you have [Docker Desktop](https://www.docker.com/products/docker-desktop) and WSL2 (if on Windows) installed.

### Installation

1. **Clone the repository:**
```bash
   git clone https://github.com/omar-920/smart-fleet-api.git
   cd smart-fleet-api
```

2. **Install Composer dependencies:**
```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
```

3. **Environment Setup:**
```bash
   cp .env.example .env
```
   *(Ensure you configure your database and Redis settings in the `.env` file)*

4. **Start the Docker containers using Sail:**
```bash
   ./vendor/bin/sail up -d
```

5. **Generate App Key & Run Migrations:**
```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate
```

## 📚 API Documentation

This project features comprehensive API documentation generated automatically.

1. **Scribe Web Docs:** Once the server is running, visit `http://localhost/docs` to view the interactive API documentation.
2. **Postman Collection:** A fully configured Postman collection (`smart_fleet.postman_collection.json`) is included in the repository root for immediate API testing. Import it directly into Postman.

## 👨‍💻 Author

**Omar Tarek**

[LinkedIn](https://www.linkedin.com/in/omar-tarek-59a782262/)
