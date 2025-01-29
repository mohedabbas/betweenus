#!/bin/sh
set -e

echo ">>>> Waiting for MariaDB to be ready..."
# Optional: wait for a few seconds or do a more sophisticated wait-for check
sleep 10

echo ">>>> Running migrations..."
php /home/php/migration_script.php up

echo ">>>> Starting PHP-FPM..."
# Replace this with the command that starts your PHP service, e.g. php-fpm
exec php-fpm

#  The  startup.sh  script is a shell script that runs when the container starts. It waits for MariaDB to be ready, runs the migration script, and then starts PHP-FPM.
#  The  migration_script.php  script is a PHP script that runs the database migrations.
#  The  Dockerfile  is a file that contains instructions for building the Docker image. It specifies the base image, copies the PHP files into the image, and sets the entrypoint to the  startup.sh  script.
#  The  docker-compose.yml  file is a file that defines the services, networks, and volumes for the Docker containers. It specifies the PHP and MariaDB services, the network they should be connected to, and the volumes that should be mounted.
#  The  docker-compose.override.yml  file is a file that overrides the default settings in the  docker-compose.yml  file. It specifies the environment variables for the PHP service, such as the database host, username, and password.
#  The  .env  file is a file that contains the environment variables for the PHP service. It specifies the database host, username, and password.
#  The  migration_script.php  file is a PHP script that runs the database migrations. It connects to the database using the environment variables specified in the  .env  file and runs the migrations using the Phinx library.
#  The  phinx.yml  file is a configuration file for the Phinx library. It specifies the database connection details and the migration paths.
#  The  migrations/  directory is a directory that contains the database migration files. Each migration file contains the SQL queries to create or modify the database schema.
#  The  php/  directory is a directory that contains the PHP files for the application. It contains the  index.php  file, which is the entry point for the application.
#  The  php/Dockerfile  file is a file that contains instructions for building the PHP Docker image. It specifies the base image, copies the PHP files into the image, and sets the entrypoint to the  startup.sh  script.
#  The  php/startup.sh  file is a shell script that runs when the PHP container starts. It waits for MariaDB to be ready, runs the migration script, and then starts PHP-FPM.
#  The  php/migration_script.php  file is a PHP script that runs the database migrations. It connects to the database using the environment variables specified in the  .env
