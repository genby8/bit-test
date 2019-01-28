<?php

namespace Application\Form\Account;

use Application\Form\Form;
use Respect\Validation\Rules\FloatVal;
use Respect\Validation\Rules\IntVal;
use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Rules\Numeric;
use Respect\Validation\Rules\OneOf;
use Respect\Validation\Rules\Positive;
use Respect\Validation\Rules\StringType;

class TakeBalance extends Form
{

    public function __construct()
    {
        $this->addValidator('amount', new StringType())
            ->addValidator('amount', new NotEmpty())
            ->addValidator('amount', new Numeric())
            ->addValidator('amount', new Positive())
            ->addValidator('amount', new OneOf(new IntVal(), new FloatVal()));
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->filterData();
        $data = $this->getData();
        return $this->isValidPartial($data);
    }
}