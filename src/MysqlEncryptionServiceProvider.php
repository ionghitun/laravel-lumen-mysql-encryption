<?php

namespace IonGhitun\MysqlEncryption;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Class MysqlEncryptionServiceProvider
 *
 * @package IonGhitun\MysqlEncryption\Providers
 */
class MysqlEncryptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->addValidators();
    }

    /**
     * Add validators for unique and exists.
     */
    private function addValidators()
    {
        /**
         * Validate unique binary encrypted
         */
        Validator::extend('unique_encrypted', function ($attribute, $value, array $parameters) {
            if (!isset($parameters[0])) {
                throw new \Exception('unique_encrypted requires at least one parameter');
            }

            $table = $parameters[0];
            $field = isset($parameters[1]) ? $parameters[1] : $attribute;

            $items = DB::select("SELECT count(*) as aggregate FROM `" . $table . "` WHERE AES_DECRYPT(`" . $field . "`, '" . env("ENCRYPTION_KEY") . "') LIKE '" . $value . "' COLLATE utf8mb4_general_ci");

            if ($items[0]->aggregate > 0) {
                return false;
            } else {
                return true;
            }
        });

        /**
         * Validate exists binary encrypted
         */
        Validator::extend('exists_encrypted', function ($attribute, $value, array $parameters) {
            if (!isset($parameters[0])) {
                throw new \Exception('exists_encrypted requires at least one parameter');
            }

            $table = $parameters[0];
            $field = isset($parameters[1]) ? $parameters[1] : $attribute;

            $items = DB::select("SELECT count(*) as aggregate FROM `" . $table . "` WHERE AES_DECRYPT(`" . $field . "`, '" . env("ENCRYPTION_KEY") . "') LIKE '" . $value . "' COLLATE utf8mb4_general_ci");

            if ($items[0]->aggregate < 1) {
                return false;
            } else {
                return true;
            }
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
