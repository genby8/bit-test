<?php

namespace Application\Model;

class Entity
{
    /**
     * @var array
     */
    protected $fields = [];

    public function __construct(array $data = null)
    {
        if (!is_null($data)) {
            $this->setData($data);
        }
        return $this;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($this->fields as $fieldName => $fieldValue) {
            $this->setField($fieldName, (isset($data[$fieldName])) ? $data[$fieldName] : null);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getField($name);
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        $this->setField($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getField(string $name)
    {
        if ($this->isFieldExists($name)) {
            return $this->fields[$name];
        } else {
            throw new \Error();
        }
    }

    /**
     * @param string $name
     * @param $value
     */
    protected function setField(string $name, $value)
    {
        if ($this->isFieldExists($name)) {
            $this->fields[$name] = $value;
        } else {
            throw new \Error();
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isFieldExists(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->isFieldExists($name);
    }
}