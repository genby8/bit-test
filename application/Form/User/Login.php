<?php

namespace Application\Form\User;


class Login
{
    protected $data = [];

    public function __construct()
    {
    }

    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    private function filterData()
    {
        foreach ($this->data as $k => $v) {
            $this->data[$k] = strip_tags($v);
        }
    }

    public function isValid()
    {
        $this->filterData();
        return true;
    }

    public function getMessagesError()
    {
        return [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValue($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }
}

