
# Laravel-geographical-api-restriction package

The laravel geographical api restriction is a Laravel package that allows you to restrict access to api routes based on geographic locations. You can apply restrictions at the country or region level using customizable configurations.

## Features
- Restrict api routes by country or region.
- Middleware integration with Laravel.
- Customizable configurations for easy setup.

# Installation

To install the package, run the command:

```bash
composer require aimsinfosoft/geo-restriction
```

After installation, you need to publish the configuration file:

```bash
php artisan vendor:publish --tag=georestriction-config
```

After installation, you need to Change in ```config/georestriction.php``` file.

Locate the following line:
```config file
return [
    'restriction' => 1
    'allowed_countries' => ['IN','CA'],
    'blocked_countries' => ['CN'],
    'allowed_regions' => ['GJ', 'QC'],
    'blocked_regions' => ['GD'],
    'error_message' => 'Access denied due to geographical restrictions.' 
];
```

Change it according to your preference: 

#### 1. Set Restriction Type:

* restriction: Set to 0 for no restriction, 1 for country-based restrictions, or 2 for region-based restrictions.
#### 2. Allow or Block Countries:

* Use allowed_countries to specify the countries you want to allow.
* Use blocked_countries to specify the countries you want to block.
#### 3. Allow or Block Regions:

* If restriction is set to 2, use allowed_regions for regions to allow and blocked_regions for regions to block.
#### 4. Customize the Error Message:

* Modify error_message to set the message displayed to users in restricted areas.


### Routes Configuration

After making the changes, you have to apply restrictions to your api routes, add the ```geo.restriction``` middleware in ```routes/api.php``` file

#### 1. Group Middleware
To apply the restriction to a group of api routes, use the middleware in your route file:
```api
Route::middleware(['geo.restriction'])->group(function () {
    // Add all routes you want to apply restrictions to
});
````
#### 2. Single Route Middleware
To apply the restriction to individual api routes, use the ```middleware()``` method directly:
```api
Route::get('/restricted-data', function () {
    return response()->json();
})->middleware('geo.restriction');
````


## Example Workflow

    1. Install the package using Composer.
    2. Publish the configuration file.
    3. Update config/geo-restriction.php with your restrictions:
        * Specify allowed or blocked countries/regions.
        * Choose the restriction type (0, 1, or 2).
        * Set the custom error message.
    4. Apply the geo.restriction middleware to your routes as described above.
### Example

Here is example of restricting users based on their geographical locations.

After successfully adding package and publishing config file:

#### Step 1: Update the Configuration File
Open the ```config/geo-restriction.php``` file and configure the restrictions. For example:

```configure
return [
    'restriction' => 1,                     // Enable country-wise restriction
    'allowed_countries' => ['US', 'IN'],    // Allow users from the United States and India
    'blocked_countries' => ['CN'],          // Block users from China
    'allowed_regions' => [],                // As we apply restriction to country wise so make it empty
    'blocked_regions' => [],                // As we apply restriction to country wise so make it empty
    'error_message' => 'Access denied due to geographical restrictions.', // Custom error message
];
```
#### Step 2:  Add API Routes with Middleware

Apply the middleware to specific routes or groups in ```routes/api.php``` . For example:

```routes
// Group Middleware Example
Route::middleware(['geo.restriction'])->group(function () {
    Route::get('/user/profile', function (Request $request) {
        return response()->json([
            'message' => 'Access granted!',
            'user' => [
                'name' => 'Test',
                'email' => 'test@example.com',
            ],
        ]);
    });

    Route::get('/orders', function () {
        return response()->json([
            'message' => 'Here are your orders!',
        ]);
    });
});

// Single Route Middleware Example
Route::post('/user/update', function (Request $request) {
    return response()->json([
        'message' => 'Profile updated successfully!',
    ]);
})->middleware('geo.restriction');
```

#### Step 3: Test API Endpoints and See the Result

1 From an allowed countries (e.g., United States, India (```US-IN```)):
* Access ```GET /api/user/profile```: Returns a JSON response with the user's profile.
* Access ```GET /api/orders```: Returns a JSON response with order details.
* Access ```POST /api/user/update```: Returns a JSON response confirming the profile update.

Example Response for ```/api/user/profile```:

```exmple
{
    "message": "Access granted!",
    "user": {
        "name": "Test",
        "email": "test@example.com"
    }
}

```
2 From a blocked countries and except from allowed countries (e.g. China (```CN```)):

Example Response :

```exmple
{
    "message": "Access denied due to geographical restrictions."
}

```
