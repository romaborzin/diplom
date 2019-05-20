<?


class MessageController extends \Phalcon\Mvc\Controller
{
public function createAction($room_id, $text)
    {
         if ($this->request->isAjax()) {
            $message = new Message();
            $user_id = $this->session->auth['id'];
            $user = User::findFirst($user_id);
            $room = Room::findFirst($room_id);
            // Сохраняем и проверяем на наличие ошибок
            $success = $message->save(
                [
                    'date_time' => date("Y-m-d H:i:s"),
                    'text' => $text,
                    'user_id' => $user_id,
                ]
            );
                if ($success) {
                $roomHasMessage = new RoomHasMessage();
                $roomHasMessage->Message = $message;
                $roomHasMessage->Room = $room;
                $success = $roomHasMessage->save([]);
                if ($success) {
                     $this->view->disable();
                    return false;
        
                } else {
                    echo "К сожалению, возникли следующие проблемы: ";
                    $messages = $room->getMessages();
                    foreach ($messages as $message) {
                        echo $message->getMessage(), "<br/>";
                    }
                }
            } else {
                $messages = $message->getMessages();
                foreach ($messages as $message) {
                    $this->flash->error($message->getMessage());
                }
                $this->view->disable();
                    return false;
        
            }
    }
    }
    }