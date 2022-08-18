<?php
namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class AddUserController extends Controller
{
    public $email;
    public $name;
    public $password;

    public function options($actionID)
    {
        return ['email', 'name', 'password'];
    }

    public function optionAliases()
    {
        return ['e' => 'email', 'm' => 'name', 'p' => 'password'];
    }

    public function actionIndex()
    {
        $user = new User();
        $user->email = $this->email;
        $user->username = $this->name;
        $user->setPassword($this->password);
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();

        try {
            if ($user->save()) {
                echo "***********\n";
                echo "User {{$this->name}} added\n";
                echo "***********\n";

                return ;
            }
        } catch (\Throwable $exception) {
            // skipp
        }

        echo "***********\n";
        echo "User {{$this->name}} not added\n";
        echo "***********\n";

    }
}
