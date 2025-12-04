# laravel12-api-integration

A simple Laravel 12 project demonstrating how API mode works, including
project setup, enabling API features, and understanding what changes
happen before and after running the `php artisan install:api` command.

---

## 🚀 1. Introduction

Laravel 12 provides a clean structure for building APIs.  
Initially, Laravel does **not** include API authentication or API scaffolding.

The command:

php artisan install:api


automatically enables **API mode** with:

- API routing support  
- Laravel Sanctum for token authentication  
- Basic middleware setup  

---

## 🛠️ 2. Project Setup

### Step 1 — Create Laravel 12 Project

composer create-project laravel/laravel:^12.0 laravel12-api-integration
cd laravel12-api-integration


At this stage:

- No `api.php` routes file exists  
- No Sanctum  
- No API auth  

---

## 🔧 3. Enable API Mode

Run:

php artisan install:api


### ✔ Before running the command  
- `routes/api.php` → **does NOT exist**  
- No API middleware  
- No Sanctum, no token system  

### ✔ After running the command  
Laravel creates the API structure:

- `routes/api.php` file appears  
- Sanctum installed & configured  
- Default `/api/user` route added  
- API middleware enabled  

---

## 📂 4. Default API Routes

After installing API mode, Laravel generates this file:

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

🧪 5. Add a Test API Route
To confirm the API is working, add this to routes/api.php:

Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
});
Now test in browser:


http://localhost:8000/api/test
You should see:

{
  "message": "API working!"
}

✅ 6. Run the Project

php artisan serve
🎉 Your Laravel12-api-integration is Ready!
You can now start integrating external APIs or creating your own API endpoints.
