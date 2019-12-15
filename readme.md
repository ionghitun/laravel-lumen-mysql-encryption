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
