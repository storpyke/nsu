<?php

declare(strict_types=1);

namespace Algetar\Nsu\Tests\Model;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Exception\UnknownPropertyException;
use Algetar\Nsu\Model\Model;
use Algetar\Nsu\Spell;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    private Model $model;

    protected function setUp(): void
    {
        $this->model = new Model([
            0 => ['titles' => ['ноль']],
        ]);
    }

    /**
     * @throws UnknownIndexException
     */
    public function testItCanFindAndGetRow()
    {
        $row = $this->model->find(0)->row();

        self::assertEquals(['titles' => ['ноль']], $row);
    }

    /**
     * @throws UnknownIndexException
     */
    public function testItCanGetProperty()
    {
        $titles = $this->model->find(0)->titles;

        self::assertEquals(['ноль'], $titles);
    }

    /**
     * @throws UnknownIndexException
     */
    public function testName()
    {
        $this->model->find(0);

        self::assertEquals('ноль', $this->model->name(Spell::GENDER_FEMALE));
    }

    /**
     * @throws UnknownIndexException
     */
    public function testUnknownPropertyException()
    {
        self::expectException(UnknownPropertyException::class);

        $this->model->find(0)->type;
    }

    public function testUnknownIndexException()
    {
        self::expectException(UnknownIndexException::class);

        $this->model->find(1);
    }
}
