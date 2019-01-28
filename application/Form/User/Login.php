<?php

namespace Application\Form\User;

use Application\Form\Form;
use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Rules\StringType;

class Login extends Form
{

    public function __construct()
    {
        $this->addValidator('email', new StringType())
            ->addValidator('email', new NotEmpty())
            ->addValidator('password', new StringType())
            ->addValidator('password', new NotEmpty());
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $this->filterData();
        $data = $this->getData();
        return $this->isValidPartial($data);
    }
}