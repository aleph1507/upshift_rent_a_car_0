##about
Uses Laravel Passport.

##routes
### auth
    - POST /api/register
        - form-data
            - firstName : String, required
            - lastName : String, required
            - dateOfBirth : date
            - phoneNumber : String
            - role : enumeration(business, customer)
            - email : String, required, email, unique in users
        - PassportController@register
        
    - POST /api/login
        - form-data
            - email
            - password
        - PassportController@login
        
    - POST http://127.0.0.1:8000/api/logout
        - auth middleware    
        
### user
    - GET /api/user
        - auth middleware
        - currently logged user's profile  
        
    - PUT /api/user   
        - auth middleware
        - update currently logged in user's profile
        - x-www-form-urlencoded (Laravel will not parse form-data parameters via PUT)
            - firstName : String, required
            - lastname : String, required
            - date : date, required
            - phoneNumber : String
            - email : String, required, email, unique in users excluding the current user's email
            - password 

### locations
    - GET /api/locations
        - auth middleware
        - overview_locations scope
        - without query params returns the current user's locations resource collection
        - takes query parameter filter designating by which column should be filtered, either address or name
        - takes query parameter q as a value to filter by
        - ex. rentacar.dev/api/locations?filter=address&q=orce will query for locations located at orce
        - locations resource consists of owners email, locations email, name, latitude, longitude, phone number, address, total number of cars, number of available cars, number of rented cars
        
    - POST /api/locations
        - auth middleware
        - create_locations scope
        - form-data
            - email
            - name
            - address
            - latitude
            - longitude
            - phoneNumber
        - LocationController@store
        
    - GET /api/locations/{id}
        - auth middleware
        - view_locations scope
        
    - PUT /api/locations/{id}
        - auth middleware
        - update_locations scope
        - x-www-form-urlencoded
            - email
            - name
            - address
            - latitude
            - longitude
            - phoneNumber
         - LocationController@update
         
    - DELETE /api/locations/{id}
        - auth middleware
        - delete_locations scope
        - LocationController@destroy
        
### cars
    - GET /api/cars
        - auth middleware
        - overview_cars scope
        - with query params returns the current user's cars
        - takes brand, model, year query parameters to search by
        - takes query param filter
        - filter can take status, location or priceRange
        - ex. /api/cars?brand=Renault&model=R4&year=2019&filter=priceRange&min=700&max=900 will search for a Renault R4 made in 2019 that rents for a price per day between 700 and 900

    - POST /api/cars                                     
        - auth middleware
        - create_cars scope
        - form-data
            - location_id
            - brand
            - model
            - year
            - typeOfFuel
            - status, enumeration(available, not_available, rented)
            - pricePerDay
        - CarController@store
        
    - PUT /api/cars/{id}
        - auth middleware
        - update_cars scope
        - x-www-form-urlencoded
            - location_id
            - brand
            - model
            - status
            - pricePerDay
        - CarController@update
        
    - DELETE /api/cars/{id}
        - auth middleware
        - delete_cars scope
        - CarController@destroy
        
### rents
    - GET /api/rents
        - auth middleware
        - overview_rents scope
        - RentController@index
        - returns current users rents
        
    - POST /api/rents
        - auth middleware
        - create_rents scope
        - form-data
            - car_id
            - rented_at
        - RentController@store                             
        - customer rents a car
        
    - PUT /api/rents/{id}
        - auth middleware
        - update_rents scope
        - x-www-form-urlencoded
            - returning_location_id
            - car_id
            - returned_at
        - RentController@update
        - rented car is returned 
        
    - DELETE /api/rents/{id}
        - auth middleware
        - delete_rents scope
        - RentController@destroy
        - business user deletes rent record       
