<?php

namespace Application\Model;

class Entity
{
    protected $fields = [];

    public function __construct(array $data = null)
    {
        if (!is_null($data)) {
            $this->setData($data);
        }
        return $this;
    }

    public function setData(array $data)
    {
        foreach ($this->fields as $fieldName => $fieldValue) {
            $this->setField($fieldName, (isset($data[$fieldName])) ? $data[$fieldName] : null);
        }
    }

    public function __get(string $name)
    {
        return $this->getField($name);
    }

    public function __set(string $name, $value)
    {
        $this->setField($name, $value);
    }

    protected function getField(string $name)
    {
        if ($this->isFieldExists($name)) {
            return $this->fields[$name];
        } else {
            throw new \Error();
        }
    }

    protected function setField(string $name, $value)
    {
        if ($this->isFieldExists($name)) {
            $this->fields[$name] = $value;
        } else {
            throw new \Error();
        }
    }

    public function isFieldExists(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }

    public function __isset(string $name): bool
    {
        return $this->isFieldExists($name);
    }
}