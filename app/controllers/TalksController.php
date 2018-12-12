<?php


use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class TalksController extends \Phalcon\Mvc\Controller
{
	public function indexAction(){
		$numberPage = $this->request->getQuery("page", "int");
		$type = 'talks';
		$room = Room::find("type = 'talks'");
		// Создаём пагинатор, отображаются 4 элемента на странице, начиная с текущей - $currentPage
		$paginator = new PaginatorModel(
			[ 
				"data" => $room,
				"limit" => 4,
				"page" => $currentPage,
			]
		);
		// Получение результатов работы ппагинатора 
		$this->view->page = $paginator->getPaginate();
	}

	public function createAction(){
 		$room = new Room();
        $user_id = $this->session->auth['id'];
        $user = User::findFirst($user_id);
        $type ="talks";
        $role = "manager";
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        // Сохраняем и проверяем на наличие ошибок
        $success = $room->save(
            [
                'date_time' => date("Y-m-d H:i:s"),
                'name' => $name,
                'description' => $description,
                "type" => $type,

            ]
        );
               
        if ($success) {
            $userHasRoom = new UserHasRoom();
            $userHasRoom->User = $user;
            $userHasRoom->Room = $room;
            $success = $userHasRoom->save(['role'=>$role,]);
            if ($success) {
                $this->dispatcher->forward(
                    [
                        'controller' => 'room',
                        'action' => 'edit',
                        'params' => ['room_id' => $room->room_id],
                    ]
                );
            } else {
                echo "К сожалению, возникли следующие проблемы: ";
                $messages = $room->getMessages();
                foreach ($messages as $message) {
                    echo $message->getMessage(), "<br/>";
                }
            }
        } else {
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $room->getMessages();
            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
	}

	public function saveAction($user_id=0)
    {
        if (!$this->request->isPost()) {
            $auth = $this->session->auth;
            
            
                $room = Room::findFirstByuser_id($auth['id']);
            
            if (!$user) {
                $this->flash->error("Пользователь не найден");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->user_id = $user->user_id;
            $this->tag->setDefault("user_id", $user->user_id);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("second_name", $user->second_name);
            
        }
    }
}