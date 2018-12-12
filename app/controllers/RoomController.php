<?php

class RoomController extends ControllerBase
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

     public function indexAction($room_id = null, $user_id = null)
    {
        // Получаем запрошенный курс
        $room = Room::findFirst($room_id);
        $user = User::findFirst($user_id);
        $user_room = UserHasRoom::find($room_id);

        $this->view->room = $room;
        $this->view->user = $user;
        $this->view->user_room = $user_room;
    }
    public function saveAction($room_id=0)
    {
        
    }
     public function speackerAction($room_id=0)
    {
        
    }
}