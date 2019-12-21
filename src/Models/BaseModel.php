<?php

namespace IonGhitun\MysqlEncryption\Models;

use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class BaseModel
 *
 * @package IonGhitun\MysqlEncryption\Models
 */
class BaseModel extends Model
{
    /**
     * Elements should be the names of the fields
     *
     * @var array
     */
    protected $encrypted = [];

    /**
     * Elements should be pairs with key column name and value and array with type and optional parameters
     *
     * @example ['age' => ['randomDigit']]
     * @example ['age' => ['numberBetween', '18','50']]
     *
     * Available types: https://github.com/fzaninotto/Faker
     *
     * @var array
     */
    protected $anonymizable = [];

    /**
     * Get model attribute
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (array_key_exists($key, $this->relations) || method_exists($this, $key)) {
            return $value;
        } else {
            $value = parent::getAttribute($key);
        }

        if (in_array($key, $this->encrypted)) {
            $value = $this->aesDecrypt($value);
        }

        return $value;
    }

    /**
     * Descrypt value
     *
     * @param $val
     * @param string $cypher
     * @param bool $mySqlKey
     *
     * @return false|string
     */
    protected function aesDecrypt($val, $cypher = 'aes-128-ecb', $mySqlKey = true)
    {
        $secret = env('ENCRYPTION_KEY');

        $key = $mySqlKey ? $this->generateMysqlAesKey($secret) : $secret;

        return openssl_decrypt($val, $cypher, $key, true);
    }

    /**
     * Generate encryption key
     *
     * @param $key
     *
     * @return string
     */
    protected function generateMysqlAesKey($key)
    {
        $generatedKey = str_repeat(chr(0), 16);

        for ($i = 0, $len = strlen($key); $i < $len; $i++) {
            $generatedKey[$i % 16] = $generatedKey[$i % 16] ^ $key[$i];
        }
        return $generatedKey;
    }

    /**
     * Set model attribute
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Model
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypted)) {
            $value = $this->aesEncrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Encrypt value
     *
     * @param $val
     * @param string $cypher
     * @param bool $mySqlKey
     *
     * @return string
     */
    protected function aesEncrypt($val, $cypher = 'aes-128-ecb', $mySqlKey = true)
    {
        $secret = env('ENCRYPTION_KEY');

        $key = $mySqlKey ? $this->generateMysqlAesKey($secret) : $secret;

        return openssl_encrypt($val, $cypher, $key, true);
    }

    /**
     * Get attributes array
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encrypted as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $this->aesDecrypt($attributes[$key]);
            }
        }

        return $attributes;
    }

    /**
     * Get original not encrypted
     *
     * @param null $key
     * @param null $default
     *
     * @return array|mixed|string
     */
    public function getOriginal($key = null, $default = null)
    {
        if (in_array($key, $this->encrypted)) {
            return $this->aesDecrypt(Arr::get($this->original, $key, $default));
        }

        return Arr::get($this->original, $key, $default);
    }

    /**
     * Get encrypted columns
     *
     * @return array
     */
    public function getEncrypted()
    {
        return $this->encrypted;
    }

    /**
     * Get anonymizable columns
     *
     * @return array
     */
    public function getAnonymizable()
    {
        return $this->anonymizable;
    }

    /**
     * where for encrypted columns
     *
     * @param $query
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function scopeWhereEncrypted($query, $field, $value)
    {
        return $query->whereRaw('AES_DECRYPT(' . $field . ', "' . env("ENCRYPTION_KEY") . '") LIKE "' . $value . '" COLLATE utf8mb4_general_ci');
    }

    /**
     * orWhere for encrypted columns
     *
     * @param $query
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function scopeOrWhereEncrypted($query, $field, $value)
    {
        return $query->orWhereRaw('AES_DECRYPT(' . $field . ', "' . env("ENCRYPTION_KEY") . '") LIKE "' . $value . '" COLLATE utf8mb4_general_ci');
    }

    /**
     * Anonymize model fields
     *
     * @param null $locale
     */
    public function anonymize($locale = null)
    {
        $faker = Factory::create($locale ?? env('FAKER_LOCALE', Factory::DEFAULT_LOCALE));

        foreach ($this->anonymizable as $field => $type) {
            if (in_array($field, $this->attributes)) {
                $this->$field = call_user_func([$faker, $type[0]], array_slice($type, 1));
            }
        }
    }
}
