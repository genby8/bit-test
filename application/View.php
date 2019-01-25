<?php

namespace Application;


use Application\View\Model\ViewModel;

class View
{
    private $layout;

    /**
     * @param $name
     * @return mixed
     */
    public function getHelper($name)
    {
        $config = Config::getInstance()->getConfig();
        if (isset($config['view_manager']['helpers'][$name])) {
            return new $config['view_manager']['helpers'][$name];
        }
        throw new \Error();
    }

    /**
     * @param null $layoutName
     */
    public function setLayout($layoutName = null)
    {
        $this->layout = $layoutName;
    }

    function render(ViewModel $viewModel)
    {
        if (!is_null($this->layout)) {
            $layout = $this->getPath($this->layout);
        } else {
            $layout = $this->getPath('layout/layout');
        }
        extract($viewModel->getVariables());
        ob_start();
        include $this->getPath($viewModel->getTemplateName());
        $content = ob_get_contents();
        ob_end_clean();
        include $layout;
    }

    /**
     * @param $name
     * @return string
     */
    private function getPath($name)
    {
        $config = Config::getInstance()->getConfig();
        return $config['view_manager']['template_map'][$name];
    }

}