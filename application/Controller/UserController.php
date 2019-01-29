<?php

namespace Application\Controller;

use Application\ControllerAction;
use Application\Form\Account\TakeBalance;
use Application\Form\User\Login;
use Application\Model\Account;
use Application\Model\AccountTransaction;
use Application\Model\User;
use Application\Session\SessionWrapper;
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
                    $errors[] = 'Данные неверные';
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
        $user = $userModel->getCurrentUser();
        $form = new TakeBalance();
        $errors = [];
        if ($this->isMethod('post')) {
            $form->setData($_POST);
            if ($form->isValid()) {
                $data = $form->getData();
                if ((new AccountTransaction())->takeMoney($user, $data['amount'])) {
                    $this->redirectToRoute('cabinet');
                }
                $errors[] = 'Что то пошло не так, возможно не хватает баланса';
            } else {
                $errors = $form->getMessagesError();
            }
        }
        $viewModel = new ViewModel([
            'user' => $user,
            'form' => $form,
            'errors' => $errors,
            'account' => (new Account())->getBalance($user)
        ]);
        $viewModel->setTemplate('user/cabinet');
        return $viewModel;
    }
}