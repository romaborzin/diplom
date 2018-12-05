<?php



class SessionController extends \Phalcon\Mvc\Controller
{
    private function _registerSession($user)
    {
        $this->session->set(
            'auth',
            [
                'id'   => $user->user_id,
                'email' => $user->email,
                'login' => $user->login,
                'role' => 'admin',
            ]
        );
    }
    
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    'controller' => 'test',
                    'action'     => 'index',
                    'params' => [error=>'Пожалйста, не хакайте нас. Оч просим.'],
                ]
            );;
        }
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

         //найдем пользователя, подходящего под параметры
        $user = User::findFirst(
                [
                    "email = :email:",
                    'bind' => [
                        'email'    => $email
                    ]
                ]
            );

        if ($user !== false && $this->security->checkHash($password, $user->pass)) {
            /*если пользователь найден и
            пароли совпадают
            авторизуем и переходим на след страницу*/
            $this->_registerSession($user);
            return $this->dispatcher->forward(
                [
                    'controller' => 'test',
                    'action'     => 'index',
                ]
            );
        }

        return $this->dispatcher->forward(
            [
                'controller' => 'test',
                'action'     => 'index',
                'params' => [error=>'error '.$user->email.' | '.$this->security->checkHash($user->pass, $password).' | '.$password],
            ]
        );
    }

     public function logoutAction()
    {
        $this->session->destroy();
        return $this->response->redirect();
    }

}

