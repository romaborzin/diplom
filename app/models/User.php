<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="user_id", type="integer", length=11, nullable=false)
     */
    public $user_id;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=255, nullable=true)
     */
    public $email;

  

    /**
     *
     * @var string
     * @Column(column="pass", type="string", length=60, nullable=true)
     */
    public $pass;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=127, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="second_name", type="string", length=45, nullable=true)
     */
    public $second_name;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();


        $validator->add(
            "email",
            new UniquenessValidator(
                [
                    "email"   => $this,
                    "message" => "Пользователь с таким email уже существует.",
                ]
            )
        );

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Пожалуйста введите правильный email адрес.',
                ]
            )
        );
        $validator->add(
            ['email',
             'pass',
             'name',
             'second_name'],
            new PresenceOf(
                [
                    "message" => [
                            "name" => "Имя - обязательное поле.",
                            "second_name"       => "Фамилия - обязательное поле",
                            "email"       => "Email - обязательное поле",
                            "pass"       => "Пароль - обязательное поле",
                        ]
                ]
            )
        );

        $validator->add(
            [
                "name",
                "second_name"
            ],
            new RegexValidator(
                [
                    "pattern" => [
                        "name" => "/^[A-ЯЁ][a-яё]*([a-яё]|-[А-ЯЁ])[a-яё]*$/",
                        "second_name"       => "/^[A-ЯЁ][a-яё]*([a-яё]|-[А-ЯЁ])[a-яё]*$/",
                    ],
                    "message" => [
                        "name" => "Неверно введено имя.",
                        "second_name"    => "Неверно введена фамилия.",
                    ]
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("diplom");
        $this->setSource("user");
        $this->hasManyToMany(
            'user_id',
            'UserHasRoom',
            'user_user_id', 'room_room_id',
            'Room',
            'room_id'
        );
        $this->hasManyToMany(
            'user_id',
            'RoomHasMessage',
            'user_id', 'message_message_id',
            'Message',
            'message_id'
        );
        $this->hasMany('user_id', 'UserHasRoom', 'user_user_id', ['alias' => 'UserHasRoom']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    public function getType()
    {
        switch ($this->type) {
            case 'admin':
                return 'Администратор';         
            default:
                return null;
        }
    }

}
