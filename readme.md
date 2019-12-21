[![Latest Stable Version](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/v/stable)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)
[![Build Status](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/build-status/master)
[![Total Downloads](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/downloads)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ionghitun/laravel-lumen-mysql-encryption/?branch=master)
[![License](https://poser.pugx.org/ionghitun/laravel-lumen-mysql-encryption/license)](https://packagist.org/packages/ionghitun/laravel-lumen-mysql-encryption)


# Laravel Mysql Encryption

Database fields encryption in laravel and lumen for mysql databases with native search.

## Instalation notes

`$ composer require ionghitun/laravel-lumen-mysql-encryption`

## Documentation:

Service provider is automatically loaded for laravel, for lumen you need to add 

    $app->register(IonGhitun\MysqlEncryption\MysqlEncryptionServiceProvider::class);
    
to your `bootstrap/app.php` file.

You need to add `ENCRYPTION_KEY` to your `.env` file, has to be 16 chars long.

Each of your Models should extend `IonGhitun\MysqlEncryption\Models\BaseModel` and for the fields you want to be encrypted you have to do the following:

- in migrations set field to `binary`
- add `$encrypted` to your model:
    
        /** @var array */
        protected $encrypted = [
            'name',
            'email'
        ];
        
You can use Validator on these fields with:

- unique_encrypted

        unique_encrypted:<table>,<field(optional)>
        
- exists_encrypted

        exists_encrypted:<table>,<field(optional)>
