<?php


use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class ConferenceController extends \Phalcon\Mvc\Controller
{
	public function indexAction(){
		$numberPage = $this->request->getQuery("page", "int");
		$type = 'conference';
		$room = Room::find("type = 'conference'");
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
        $type ="conference";
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

}