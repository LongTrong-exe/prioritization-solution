# Flowfact importer service

Solution to request the prioritization of onOffice enterprise tasks.
 
 ### Introduction
This application does:

 - Web UI for saving flowfact-API key
 - Web UI for User Mapping
 - Background Jobs for data importing

### Installation
Deployment has the following dependencies:

#### Required
* PHP 7.4
* Composer
* MySQL >= 5.7 for queue processing

Create .env file
```
cp .env.example .env
```

Install dependencies
```
composer install
```

Create database structure
```
php artisan migrate
```

Queue processing script
```
php artisan queue:work
```

Fill in environment parameters in .env

* APP_KEY: application secret key
* ONOFFICE_PROVIDER_SECRET: key to validate request from onOffice
* CONFIGURATION_SERVICE: endpoint of configuration service
* SIGNATURE_HEADER: authentication header for communication with configuration service

### Update PHP packages
```
composer update
```
