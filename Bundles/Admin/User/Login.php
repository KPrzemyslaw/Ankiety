<?php
/**
 * Class Login
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\User;

use KPrzemyslaw\Actions\KPController;

/**
 * Class Index
 * @package Bundles\Admin\User
 */
class Login extends KPController
{
    public function run()
    {
        $this->user->signIn(
            $this->request->getData('login'),
            $this->request->getData('password')
        );

        return [
            'user' => $this->user->getData()
        ];
    }
}
