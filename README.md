## Transportation Management System

this app built with laravel 12, filament v4, pest v3, tailwindcss v4

### Installation

1. Clone the repository:

    ```bash
    git clone git@github.com:Moyhe/transportation_management_app.git

2. Navigate into project folder using terminal and run

    ```bash
    composer install && npm install    

3. Copy .env.example into .env

    ```bash
   cp .env.example .env

4. Adjust DataBase parameters
   If you want to use Mysql, make sure you have mysql server up and running.

5. Set encryption key

    ```bash
    php artisan key:generate

6. Run migrations and seed the database

    ```bash
    php artisan migrate --seed

7. run test
    ```bash
    php artisan test

8. start the development server

    ```bash
    composer run dev

9. create filament user using

    ```bash
    php artisan make:filament-user

10. Access the application in your web browser at with filament admin panel credentials

    ```bash
    http://127.0.0.1:8000/admin/login

## Key Design Decisions

### Overlapping Trip Validation

A trip is considered active if:

    start_time <= now AND end_time >= now

This ensures that:

1- A driver cannot be assigned to two active trips at the same time.

2- A vehicle cannot be used in two active trips simultaneously.

3- When creating or editing a trip, the system checks for time conflicts before saving.

### Query Optimization

To improve performance:

    Eager loading (with(['company', 'driver', 'vehicle'])) is used throughout resources to avoid N+1 query issues.

Caching is applied to KPI widgets (active trips, available drivers, available vehicles, completed trips this month) with
a 5-minute TTL. This reduces database load and speeds up dashboard rendering.

### Assumptions

1- Each trip belongs to exactly one driver, one vehicle, and one company.

2- A driver cannot be in more than one active trip at the same time.

3- A vehicle cannot be in more than one active trip at the same time.

4- "Available drivers/vehicles" means those not currently assigned to an active trip.

5- Trips are assumed to last anywhere from 30 minutes to 8 hours.

6- KPI calculations are refreshed every 5 minutes via caching

### Testing

We focused on covering the business logic of the system: resource management, validation, and overlapping trip checks.

#### What is tested

#### Company Resource

1- Creating and editing companies

2- Validation rules (e.g., unique name)

#### Driver Resource

4- Creating and editing drivers

5- Validation rules (company assignment required, name required, etc.)

#### Vehicle Resource

1- Creating and editing vehicles

2- Validation rules (company assignment required, name required, etc.)

#### Trip Resource

1- Trip creation with valid start/end times

2- Prevention of overlapping trips (for drivers and vehicles)

3- Editing trips without conflicts

5- Validation rules (company_id, driver_id, vehicle_id, start_time, end_time required)

#### Dashboard KPIs

1- Active trips count

2- Available drivers/vehicles

3- Completed trips this month
