# Laravel Fresh - Dockerfile

### Hot to use it
1. Run `docker-compose up -d`
2. Open container terminal by running `docker exec -it laravel-fresh bash`
3. Install laravel inside of container by running `composer create-project laravel/laravel Laravel`
4. Move laravel `mv ./Laravel/* ./ && rm -rf Laravel`
5. Go to: http://localhost:8080


### Change PHP version
1. Open `Dockerfile` from `docker` directory and change the variable `PHP_VERSION`
2. Open `fpm-pool.conf` from `docker/linux` directory and change the socket version
3. Open `supervisord.conf` from `docker/linux` directory and change the `php-fpm8.3` to your version
4. Restart docker `docker-compose restart`