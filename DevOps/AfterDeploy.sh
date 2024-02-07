#!/bin/bash
cd /var/www/html/auto-deploy/reachomation
echo 'set file permissions'
sudo chown -R ubuntu:ubuntu .
sudo chown -R www-data storage
sudo chmod -R u+x .
sudo chmod g+w -R storage
echo 'copying env file.'
if [ "$DEPLOYMENT_GROUP_NAME" == "ReachoStagingDeploymentGroup" ]
then
    cp -rf .env.staging .env
elif [ "$DEPLOYMENT_GROUP_NAME" == "ReachoProdDeploymentGroup" ]
then
    cp -rf .env.reacho .env
fi
echo 'installing composer dependencies'
composer install --optimize-autoloader
echo 'running migration(forced)'
php artisan migrate --force --no-interaction
echo 'running essential artisan commands'
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan config:clear
