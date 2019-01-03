<?php

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
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

    public function searchAction()
    {
     
         $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Room', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "room_id";

        $room = Room::find($parameters);
        if (count($room) == 0) {
            $this->flash->notice("По результатам поиска, комната не найдена");

            $this->dispatcher->forward([
                "controller" => "room",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $room,
            'limit'=> 10,
            'page' => $numberPage
        ]);
        if(!$room){
            $this->flash->notice("По результатам поиска, комната не найдена");
        }

        $this->view->page = $paginator->getPaginate();
        $this->view->room = $room;
    }

	
}