<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Room extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $room_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    public $date;

    public $type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("diplom");
        $this->setSource("room");
        $this->hasManyToMany(
            'room_id',
            'UserHasRoom',
            'room_room_id', 'user_user_id',
            'User',
            'user_id'
        );
        $this->hasManyToMany(
            'room_id',
            'RoomHasMessage',
            'room_room_id', 'message_message_id',
            'Message',
            'message_id'
        );
        $this->hasMany('course_id', 'RoomHasMessage', 'room_room_id', ['alias' => 'RoomHasMessage']);
        $this->hasMany('course_id', 'UserHasRoom', 'room_room_id', ['alias' => 'UserHasRoom']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'room';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Course[]|Course|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Course|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            'name',
            new PresenceOf(
                [
                    "message" => "У комнаты должно быть название."
                ]
            )
        );

        return $this->validate($validator);
    }

}
