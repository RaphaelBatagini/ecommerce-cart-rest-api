# Hash Backend Challenge
Hash backend developer job test

## Tech Stack
- PHP 8.0.2
- Framework Lumen 8
- Docker
- Docker Compose

## Executing the application
Copy content of file **.env.example** into a new file **.env**.

Execute the following command in the project root directory:
```
$ docker-compose up -d
```

The installation of the dependencies is done when docker is initialized. Therefore, there is no need to do this procedure manually.

## Executing unit tests
To execute the unit tests, with the containers running, just use the following command:
```
$ docker-compose run phpunit
```

## Interacting with composer
To execute any action in the composer, use the following command pattern:
```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer <command>
```

## Updating gRPC clients
If some proto file is created or changed, is possible to update them using the following command:
```
$ docker run -v `pwd`:/defs namely/protoc-all -f discount.proto -l php -o app/Proto/
```
<small>In the example above, the classes for the `discount.proto` file are being generated.</small>
