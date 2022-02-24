<?php

namespace IonGhitun\MysqlEncryption;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class MysqlEncryptionServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->addValidators();
    }

    /**
     * @return void
     */
    private function addValidators(): void
    {
        /**
         * Validate unique binary encrypted
         */
        Validator::extend('unique_encrypted', function ($attribute, $value, array $parameters) {
            if (!isset($parameters[0])) {
                throw new Exception('unique_encrypted requires at least one parameter');
            }

            $field = isset($parameters[1]) ? $parameters[1] : $attribute;
            $ignore = isset($parameters[2]) ? $parameters[2] : null;

            $items = DB::select("SELECT count(*) as aggregate FROM `".$parameters[0]."` WHERE AES_DECRYPT(`".$field."`, '".getenv("ENCRYPTION_KEY")."') LIKE '".DB::getPdo()->quote($value)."' COLLATE utf8mb4_general_ci".($ignore ? " AND id != ".$ignore : ''));

            return $items[0]->aggregate === 0;
        });

        /**
         * Validate exists binary encrypted
         */
        Validator::extend('exists_encrypted', function ($attribute, $value, array $parameters) {
            if (!isset($parameters[0])) {
                throw new Exception('exists_encrypted requires at least one parameter');
            }

            $field = isset($parameters[1]) ? $parameters[1] : $attribute;

            $items = DB::select("SELECT count(*) as aggregate FROM `".$parameters[0]."` WHERE AES_DECRYPT(`".$field."`, '".getenv("ENCRYPTION_KEY")."') LIKE '".DB::getPdo()->quote($value)."' COLLATE utf8mb4_general_ci");

            return $items[0]->aggregate > 0;
        });
    }

    /**
     * @return void
     */
    public function register()
    {
    }
}
