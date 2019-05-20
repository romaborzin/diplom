<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;

use Phalcon\Mvc\View;

class RoomController extends \Phalcon\Mvc\Controller
{

	public function editAction($room_id = null)
    {
        if (!$this->request->isPost()) {
            
            
                $room = Room::findFirstByroom_id($room_id);
            
            if (!$room) {
                $this->flash->error("Комната не найдена");
                return;
            }
            $this->view->room_id = $room->room_id;
            $this->tag->setDefault("room_id", $room->room_id);
            $this->tag->setDefault("name", $room->name);
            $this->tag->setDefault("description", $room->description);
            
        }
        
    }

    public function checkAction($room_id = null)
    {
       if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    'controller' => 'room',
                    'action'     => 'list',
                    'params' => [error=>'Заполните поля', 'room_id' => $room->room_id],
                ]
            );;
        }
        $room_id    = $this->request->getPost('room_id');
        $password = $this->request->getPost('password');

         //найдем пользователя, подходящего под параметры
        $room = Room::findFirst(
                [
                    "room_id = :room_id:",
                    'bind' => [
                        'room_id'    => $room_id
                    ]
                ]
            );
        if($room->pass!='NULL'){
        if ($room !== false && $this->security->checkHash($password, $room->pass)) {
            /*если комната найдена и
            пароли совпадают
            переходим на след страницу*/
            return $this->dispatcher->forward(
                [
                    'controller' => 'room',
                    'action'     => 'index',
                    'params' => ['room_id' => $room->room_id],
                ]
            );
        }}

        return $this->dispatcher->forward(
            [
                'controller' => 'room',
                'action'     => 'index',
                'params' => [error=>'Неверный пароль'],
            ]
        );
        
    }    

     public function indexAction($room_id = null)
    {
        // Получаем запрошенный курс
        $user_id = $this->session->auth['id'];
        $room = Room::findFirst($room_id);
        $user = User::findFirst($user_id);
        $user_room = UserHasRoom::findFirst([
                'columns'    => '*',
                'conditions' => 'user_user_id = ?1 AND room_room_id = ?2',
                'bind'       => [
                    1 => $user_id,
                    2 => $room_id,
                ]
            ]
        );
        if(!$user_room){
            $ur = new UserHasRoom;
            if (!$ur->save([
                'user_user_id'=>$user_id,
                'room_room_id' => $room_id,
                'role'=>'guest',
            ]
            )) {
            $this->flash->error('Произошла ошибка: ');
            foreach ($room->getMessages() as $message) {
                $this->flash->error($message);
            }

        }}

        $this->view->room = $room;
        $this->view->user = $user;
        $this->view->user_room = $user_room;

    }

    public function saveAction($room_id=0)
    {
        if (!$this->request->isPost()) {
            $this->flash->error("Редактируйте через форму");
            $this->dispatcher->forward([
                'controller' => "room",
                'action' => 'index',
            ]);

            return;
        }

        $room_id = $this->request->getPost("room_id");
        $room = Room::findFirst($room_id);

        if (!$room) {
            $this->flash->error("Комнаты не существует " . $room_id);

            $this->dispatcher->forward([
                'controller' => "room",
                'action' => 'index',
            ]);

            return;
        }

        $room->name = $this->request->getPost("name");
        $room->description = $this->request->getPost("description");

        if (!$room->save()) {
            $this->flash->error('Произошла ошибка: ');
            foreach ($room->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "room",
                'action' => 'edit',
                'params' => ['room_id' => $room_id],
            ]);

            return;
        }

        $this->flash->success("Комната успешно обновлена");

        $this->dispatcher->forward([
            'controller' => "room",
            'action' => 'edit',
            'params' => ['room_id' => $room_id],
        ]);
    }
     public function speakerAction($room_id=0)
    {
        if (!$this->request->isPost()) {
            
            
                $room = Room::findFirstByroom_id($room_id);
            
            if (!$room) {
                $this->flash->error("Комната не найдена");
                return;
            }else
            $this->view->room = $room;
        }
        
        
    }

    public function addAction($user_id=0, $room_id=0)
    {
        $user_room = UserHasRoom::findFirst([
                'columns'    => '*',
                'conditions' => 'user_user_id = ?1 AND room_room_id = ?2 AND role = ?3',
                'bind'       => [
                    1 => $user_id,
                    2 => $room_id,
                    3 => 'speaker',
                ]
            ]
        );
        if(!$user_room){
            $ur = new UserHasRoom;
            if (!$ur->save([
                'user_user_id'=>$user_id,
                'room_room_id' => $room_id,
                'role'=>'speaker',
            ]
            )) {
            $this->flash->error('Произошла ошибка: ');
            foreach ($room->getMessages() as $message) {
                $this->flash->error($message);
            }

        }else
        $this->flash->success('Выступающий добавлен');
    }else
    $this->flash->success('Пользователь уже является выступающим');
    }

    public function listAction($type=null, $currentPage=1){
		$room = Room::find("type = '".$type."'");
		// Создаём пагинатор, отображаются 4 элемента на странице, начиная с текущей - $currentPage
		$paginator = new PaginatorModel(
			[ 
				"data" => $room,
				"limit" => 3,
				"page" => $currentPage,
			]
		);
		// Получение результатов работы ппагинатора 
        $this->view->page = $paginator->getPaginate();
        $this->view->type = $type;
    }

	public function createAction($type=null){
 		$room = new Room();
        $user_id = $this->session->auth['id'];
        $user = User::findFirst($user_id);
        $role = "manager";
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $pass = $this->request->getPost('password');
        if(!$pass){
            $pass=NULL;
        }else{
            $pass = $this->security->hash($pass);
        }
        // Сохраняем и проверяем на наличие ошибок
        $success = $room->save(
            [
                'date' => date("Y-m-d"),
                'name' => $name,
                'description' => $description,
                "type" => $type,
                'pass' => $pass,

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
                        'action' => 'index',
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

    public function resultAction($type=null)
    {
        
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Room', $_POST);
            $this->persistent->parameters = $query->getParams();
            $stype = $this->request->getPost('type');;
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "room_id";

        $room = Room::find($parameters);
        
        if(!$room){
            $this->flash->notice("По результатам поиска, комната не найдена");
            return;
        }
        else{
            $this->view->room = $room;
      $this->view->type = $stype;
        }

        
    }
    
    public function searchAction($type=null)
    {
        $this->view->type = $type;
    }
}