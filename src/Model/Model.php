<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Exception\UnknownPropertyException;
use Algetar\Nsu\Spell;

/**
 * @property string $titles
 * @property int $type
 */
class Model
{
    /* записи */
    protected array $source = [];

    /**
     * Ид текущей записи
     * @var int|string
     */
    private $id = 0;

    public function __construct(array $source = null)
    {
        if ($source !== null) {
            $this->source = array_merge($this->source, $source);
        }
    }

    public function name(int $type): string
    {
        $titles = $this->titles;

        return $titles[$type] ?: $titles[Spell::DECLENSION_MANY];
    }

    /**
     * Переводит указатель (ид) текущей записи на указанный.
     * При его отсутствии выкидывает исключение.
     *
     * @param string|int $id
     *
     * @throws UnknownIndexException
     */
    public function find($id): self
    {
        $this->id = $id;

        if (! array_key_exists($this->id, $this->source)) {
            throw new UnknownIndexException(sprintf('Unknown index %s in %s', $id, get_called_class()));
        }

        return $this;
    }

    /**
     * Текущая запись
     *
     * @throws UnknownIndexException
     */
    public function row(): array
    {
        try {
            return $this->source[$this->id];
        } catch (\Exception $exception) {
            throw new UnknownIndexException(sprintf('Unknown index %s in %s', $id, get_called_class()));
        }
    }

    public function count(): int
    {
        return count($this->source) - 1;
    }

    /**
     * @throws UnknownPropertyException|UnknownIndexException
     */
    public function __get($name)
    {
        $row = $this->row();
        if (array_key_exists($name, $row)) {
            return $row[$name];
        }

        $method = $this->getterName($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new UnknownPropertyException(sprintf('Unknown property %s in %s', $name, get_called_class()));
    }

    private function getterName($name): string
    {
        return 'get' . strtoupper(substr($name, 0,1)) . substr($name, 1);
    }
}
