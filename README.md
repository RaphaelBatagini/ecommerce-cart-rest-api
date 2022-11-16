# HTTP E-commerce Cart API.

An e-commerce cart API.

## Project Description

The API receives a POST request in its only endpoint. The payload includes a list of products and its quantities like the example below:

```json
{
    "products": [
        {
            "id": 1,
            "quantity": 1 // Product quantity to be bought
        }
    ]
}
```

The API data comes from the JSON file `products.json`, so the application has no database.

### Business Rules

#### Rule 1

For each product the API calculate the discount percentage by consuming a gRPC service provided by an external provider.

#### Rule 2

If the discount service is unavailable, the cart endpoint continue to work, but it will not calculate the discount.

#### Rule 3

If the `BLACKFRIDAY_DATE` environment variable indicates that is black friday, the API must add a free product to the cart.

Gift products have the flag `is_gift = true` and should not be accepted in requests to add them to the cart.

#### Rule 4

There must be only one free product entry in the cart.

### API Response

The API response should bring the total value of the cart with and without discount, total value of discounts and the list of products with their individual discounts. The response must respect the structure of the following example payload and all monetary values ​​must be in cents:

```json
{
    "total_amount": 20000, // Purchase total amount without discount
    "total_amount_with_discount": 19500, // Purchase total amount with discount
    "total_discount": 500, // Discount total amount
    "products": [
        {
            "id": 1,
            "quantity": 2,
            "unit_amount": 10000, // Product unity price in cents
            "total_amount": 20000, // Total amount for this product in cents
            "discount": 500, // Discount total amount in cents
            "is_gift": false // Is gift?
        },
        {
            "id": 3,
            "quantity": 1,
            "unit_amount": 0, // Product unity price in cents
            "total_amount": 0, // Total amount for this product in cents
            "discount": 0, // Discount total amount in cents
            "is_gift": true // Is gift?
        }
    ]
}
```

## Tech Stack
- PHP 8.0.2
- Framework Lumen 8
- Docker
- Docker Compose

## Application setup and execution

### Executing the application
Copy content of file **.env.example** into a new file **.env**.

Execute the following command in the project root directory:
```
$ docker-compose up -d
```

The installation of the dependencies is done when docker is initialized. Therefore, there is no need to do this procedure manually.

### Executing unit tests
To execute the unit tests, with the containers running, just use the following command:
```
$ docker-compose run phpunit
```

### Interacting with composer
To execute any action in the composer, use the following command pattern:
```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer <command>
```

### Updating gRPC clients
If some proto file is created or changed, is possible to update them using the following command:
```
$ docker run -v `pwd`:/defs namely/protoc-all -f discount.proto -l php -o app/Proto/
```
<small>In the example above, the classes for the `discount.proto` file are being generated.</small>
