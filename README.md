# Eskimi Advertising Campaign

## Development Environment Setup
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

Then, to start the service, run `./vendor/bin/sail up`

### Watch Out
If you run into this error:
> Cannot start service laravel.test: Ports are not available: listen tcp 0.0.0.0:80: bind: address already in use

Open your .env file and add `APP_PORT=preferred_port_number`
