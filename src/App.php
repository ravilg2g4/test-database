<?php

declare(strict_types=1);

namespace App;

use App\Repositories\UsersRepositoryFactory;

class App
{
    private const string HELLO_TEXT = <<<EOT
Что Вы хотите сделать с базой данных о пользователях?
Выберите номер действия:
1. Показать список пользователей;
2. Добавить пользователя;
3. Удалить пользователя.

EOT;

    public function run(): void
    {
        $dbSource = new Repositories\GetDbSource();
        $dbSource->getDbSource();
        $dbSource = $dbSource->dbSource;

        $repository = UsersRepositoryFactory::getUsersRepository($dbSource);
        $flag = true;
        while ($flag === true) {

            $goodChoice = false;
            while ($goodChoice === false) {

                switch ($_SERVER['REQUEST_METHOD']) {
                    case 'GET':
                        $repository->read();
                        $goodChoice = true;
                        break;
                    case 'POST':
                        $repository->write();
                        $repository->answerWrite();
                        $goodChoice = true;
                        break;
                    case 'DELETE':
                        $repository->deleteById();
                        $repository->answerDeleteById();

                        if ($choiceDelete === 'id') {
                            $repository->deleteId();
                        } elseif ($choiceDelete === 'email') {
                            $repository->deleteEmail();
                        }
                        $goodChoice = true;
                        break;
                    default:
                        $goodChoice = false;
                        echo PHP_EOL . 'Вы ввели некорректный ответ на вопрос. Попробуйте повторить попытку.' . PHP_EOL . PHP_EOL;
                        break;
                }
            }

            $check = readline('Вы закончили работу с базой данных? (y/n): ');
            $check = trim($check);
            if ($check === 'y') {
                $flag = false;
            }
        }
    }
}
