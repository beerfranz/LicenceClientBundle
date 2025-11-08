# LicenceClientBundle

Bundle Symfony to communicate with a licence server.

Check if new version of the product is available, get messages.

## Installation

Add the repository in `composer.json`:
```
    "repositories": [
        {"type": "vcs", "url": "https://github.com/beerfranz/LicenceClientBundle"}
    ]
```

Install the bundle:
```
composer require beerfranz/licence-client-bundle
```

For last version:
```
composer require beerfranz/licence-client-bundle:dev-main
```

Add the bundle in `config/bundles.php`:
```
Beerfranz\LicenceClientBundle\BeerfranzLicenceClientBundle::class => ['all' => true],
```

Update the database:
```
bin/console make:migration
bin/console doctrine:migration:migrate
```

## Configuration

Set this parameters in `config/services.yaml`:
```
parameters:
    product_name: default
    licence_endpoint: https://localhost/data
```

Set env vars:
* `URL`: the public URL of the application


Configure messenger (need `composer require symfony/lock symfony/scheduler symfony/messenger`)

```yaml
# config/packages/messenger.yaml
framework:
    messenger:
        transports:
            async: 'doctrine://default?queue_name=default'
```
