<?php
declare(strict_types=1);

namespace IonGhitun\MysqlEncryption\Tests\Models;

use IonGhitun\MysqlEncryption\Models\BaseModel;
use PHPUnit\Framework\TestCase;

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

        $this->assertEquals($model->getAnonymizable(), []);
    }

    /**
     * Test getEncrypted
     */
    public function testGetEncrypted()
    {
        $model = new BaseModel();

        $this->assertEquals($model->getEncrypted(), []);
    }
}
