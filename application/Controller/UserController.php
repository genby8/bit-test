<?php

namespace Application\Controller;

use Application\ControllerAction;
use Application\Form\User\Login;
use Application\Model\User;
use Application\View\Model\ViewModel;

class UserController extends ControllerAction
{
    public function loginAction()
    {
        $userModel = new User();
        if ($userModel->isAuth()) {
            $this->redirectToRoute('cabinet');
        }
        $form = new Login();
        $errors = [];
        if ($this->isMethod('post')) {
            $form->setData($_POST);
            if ($form->isValid()) {
                $data = $form->getData();
                if ($userModel->login($data['email'], $data['password'])) {
                    $this->redirectToRoute('cabinet');
                } else {
                    $errors = ['Данные неверные'];
                }
            } else {
                $errors = $form->getMessagesError();
            }
        }
        $viewModel = new ViewModel(['form' => $form, 'errors' => $errors]);
        $viewModel->setTemplate('user/login');
        return $viewModel;
    }

    public function logoutAction()
    {
        $userModel = new User();
        if (!$userModel->isAuth()) {
            $this->redirectToRoute('cabinet');
        }
        $userModel->logout();
        $this->redirectToRoute('login');
    }

    public function cabinetAction()
    {
        $userModel = new User();
        if (!$userModel->isAuth()) {
            $this->redirectToRoute('login');
        }
        $user = (new User())->getCurrentUser();
        $viewModel = new ViewModel(['user' => $user]);
        $viewModel->setTemplate('user/cabinet');
        return $viewModel;
    }
}