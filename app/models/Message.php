<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class Message extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $message_id;

    /**
     *
     * @var string
     */
    public $date_time;

    /**
     *
     * @var string
     */
    public $text;

     /**
     *
     * @var integer
     */
    public $user_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("diplom");
        $this->setSource("message");
        $this->hasManyToMany(
            'message_id',
            'RoomHasMessage',
            'message_message_id', 'room_room_id', 
            'Message',
            'room_id'
            
        );
        $this->belongsTo('room_room_id', 'Room', 'room_id', ['alias' => 'Room']);
        $this->belongsTo('user_id', 'User', 'user_id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Comment[]|Comment|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            [
                "text"
            ],
            new RegexValidator(
                [
                    "pattern" => [
                        "text" => "/^[А-Яа-яЁё\s]{1,45}/",
                        "message" => "Сообщение должно содержать русские буквы и быть длиной менее 45 символов"
                    ]
                   
                ]
            )
        );

        return $this->validate($validator);
    }
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Comment|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
