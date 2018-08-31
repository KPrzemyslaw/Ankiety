<?php
/**
 * Class Logout
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
class Logout extends KPController
{
    public function run()
    {
        $this->user->signOut();
        return [
            'user' => $this->user->getData()
        ];
    }
}
