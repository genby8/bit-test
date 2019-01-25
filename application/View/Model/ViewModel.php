<?php

namespace Application\View\Model;

class ViewModel
{

    /**
     * Alias template
     * @var string
     */
    protected $template = '';

    /**
     * View variables
     * @var array
     */
    protected $variables = [];

    /**
     * @param  array $variables
     */
    public function __construct(array $variables)
    {
        foreach ($variables as $name => $value) {
            $this->variables[(string)$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return ViewModel
     */
    public function setTemplate(string $name): self
    {
        $this->template = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->template;
    }

}
