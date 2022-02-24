<?php
declare(strict_types=1);

namespace IonGhitun\MysqlEncryption\Tests\Models;

use IonGhitun\MysqlEncryption\Models\BaseModel;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 *
 */
class BaseModelTest extends TestCase
{
    /**
     * Test getAnonymizable
     */
    public function testGetAnonymizable(): void
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getAnonymizable());
    }

    /**
     * Test getEncrypted
     */
    public function testGetEncrypted(): void
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getEncrypted());
    }

    /**
     * Test getAttribute
     */
    public function tesBaseGetAttribute(): void
    {
        $model = new BaseModel();

        $model->name = 'Test';

        $this->assertEquals('Test', $model->name);
    }

    /**
     * Test getAttribute
     */
    public function testEncryptedGetAttribute(): void
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
    public function testToArray(): void
    {
        $model = new BaseModel();

        $model->name = 'Test';

        $this->assertEquals(['name' => 'Test'], $model->toArray());
    }

    /**
     * Test toArray
     */
    public function testEncryptedToArray(): void
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
    public function testGetOriginal(): void
    {
        $model = new BaseModel();

        $this->assertEquals([], $model->getOriginal());
    }

    /**
     * Test anonymize
     */
    public function testAnonymize(): void
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
