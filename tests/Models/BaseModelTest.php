<?php
declare(strict_types=1);

namespace IonGhitun\MysqlEncryption\Tests\Models;

use IonGhitun\MysqlEncryption\Models\BaseModel;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class BaseModelTest
 *
 * @package IonGhitun\MysqlEncryption\Tests\Models
 */
class BaseModelTest extends TestCase
{
    /**
     * Test getAnonymizable
     */
    public function testGetAnonymizable()
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getAnonymizable());
    }

    /**
     * Test getEncrypted
     */
    public function testGetEncrypted()
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getEncrypted());
    }

    /**
     * Test getAttribute
     */
    public function tesBaseGetAttribute()
    {
        $model = new BaseModel();

        $model->name = 'Test';

        $this->assertEquals('Test', $model->name);
    }

    /**
     * Test getAttribute
     *
     * @throws \ReflectionException
     */
    public function testEncryptedGetAttribute()
    {
        $model = new BaseModel();

        $reflection = new ReflectionClass($model);

        $encrypted = $reflection->getProperty('encrypted');
        $encrypted->setAccessible(true);

        $encrypted->setValue($model, ['name']);

        $model->name = 'Test';

        $this->assertEquals('Test', $model->name);
    }

    /**
     * Test toArray
     */
    public function testToArray()
    {
        $model = new BaseModel();

        $model->name = 'Test';

        $this->assertEquals(['name' => 'Test'], $model->toArray());
    }

    /**
     * Test toArray
     *
     * @throws \ReflectionException
     */
    public function testEncryptedToArray()
    {
        $model = new BaseModel();

        $reflection = new ReflectionClass($model);

        $encrypted = $reflection->getProperty('encrypted');
        $encrypted->setAccessible(true);

        $encrypted->setValue($model, ['name']);

        $model->name = 'Test';

        $this->assertEquals(['name' => 'Test'], $model->toArray());
    }

    /**
     * Test getOriginal
     */
    public function testGetOriginal()
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getOriginal());
    }

    /**
     * Test anonymize
     *
     * @throws \ReflectionException
     */
    public function testAnonymize()
    {
        $model = new BaseModel();

        $reflection = new ReflectionClass($model);

        $anonymize = $reflection->getProperty('anonymizable');
        $anonymize->setAccessible(true);

        $anonymize->setValue($model, ['name' => ['text'], 'gender' => ['shuffle', 'male', 'female']]);

        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);

        $attributes->setValue($model, ['name', 'gender']);

        $model->name = 'Test';
        $model->gender = 'male';

        $model->anonymize();

        $this->assertNotEquals('Test', $model->name);
    }
}
