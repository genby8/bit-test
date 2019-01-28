<?php

namespace Application\Form;

use Respect\Validation\Validatable;

class Form
{
    /**
     * @var array
     */
    private $errorMessages = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @return array
     */
    public function getMessagesError(): array
    {
        return $this->errorMessages;
    }

    /**
     * @param string $message
     */
    public function addErrorMessage(string $message)
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
     * @param $elementName
     * @param Validatable $validator
     * @return Form
     */
    protected function addValidator(string $elementName, Validatable $validator): self
    {
        $this->validators[$elementName][] = $validator;
        return $this;
    }

    protected function filterData()
    {
        $result = [];
        foreach ($this->getData() as $k => $v) {
            $result[$k] = trim(strip_tags($v));
        }
        $this->setData($result);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValidPartial(array $data = []): bool
    {
        foreach ($data as $name => $value) {
            if (!empty($this->validators[$name])) {
                /* @var $validator Validatable */
                foreach ($this->validators[$name] as $validator) {
                    if (!$validator->validate($value)) {
                        $this->addErrorMessage('Неверное значение');
                        return false;
                    }
                }
            }
        }
        return true;
    }
}