<?


class MessageController extends \Phalcon\Mvc\Controller
{
public function createAction()
    {
        $message = new Message();
        $user_id = $this->session->auth['id'];
        $user = User::findFirst($user_id);
        $room_id = $this->request->getPost('room_id');
        $room = Room::findFirst($room_id);
        // Сохраняем и проверяем на наличие ошибок
        $success = $message->save(
            [
                'date_time' => date("Y-m-d H:i:s"),
                'text' => $this->request->getPost('text'),
                'user_id' => $user_id,
            ]
        );
            if ($success) {
            $roomHasMessage = new RoomHasMessage();
            $roomHasMessage->Message = $message;
            $roomHasMessage->Room = $room;
            $success = $roomHasMessage->save([]);
            if ($success) {
                $this->dispatcher->forward(
                    [
                        'controller' => 'room',
                        'action' => 'index',
                        'params' => ['room_id' => $room->room_id, 'user' => $user],
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
            $messages = $message->getMessages();
            foreach ($messages as $message) {
                $this->flash->error($message->getMessage());
            }
            $this->dispatcher->forward(
                [
                    'controller' => 'room',
                    'action' => 'index',
                    'params' => ['room_id' => $room->room_id, 'user' => $user],
                ]
            );
        }
    }
    }