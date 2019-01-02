<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

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
            $this->tag->setDefault("name", $room->name);
            $this->tag->setDefault("description", $room->description);
            
        }
        
        $this->view->room = $room;
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
        $numberPage = 1;
        
        $room = Room::findFirst($room_id);
        $numberPage = $this->request->getQuery("page", "int");
        $role='speaker';
        $speaker = UserHasRoom::findFirst([
                'columns'    => '*',
                'conditions' => 'role = ?1 AND room_room_id = ?2',
                'bind'       => [
                    1 => $role,
                    2 => $room_id,
                ]
            ]
        );
        if(!$speacker){

        }
        else{
             $paginator = new Paginator([
            'data' => $user,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate(); 
        $this->view->room = $room; 
        }
    }

    public function addAction($user_id=0, $room_id=0)
    {
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
                'role'=>'speaker',
            ]
            )) {
            $this->flash->error('Произошла ошибка: ');
            foreach ($room->getMessages() as $message) {
                $this->flash->error($message);
            }

        }else
        $this->flash->success('Выступающий добавлен');
    }
    }
    
}