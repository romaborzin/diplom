<?php



class RoomHasMessage extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $room_room_id;

    /**
     *
     * @var integer
     */
    public $message_message_id;

    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("diplom");
        $this->setSource("room_has_message");
        $this->belongsTo('room_room_id', 'Room', 'room_id', ['alias' => 'Room']);
        $this->belongsTo('message_message_id', 'Message', 'message_id', ['alias' => 'Message']);
        $this->belongsTo('user_id', 'User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'room_has_message';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHasCourse[]|UserHasCourse|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHasCourse|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
