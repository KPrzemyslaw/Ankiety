<?php
/**
 * Class Index.php
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\Main;

use KPrzemyslaw\Actions\KPController;

/**
 * Class Index
 * @package Bundles\Admin\Main
 */
class Index extends KPController
{
    public function run()
    {
        echo "Run Admin!";
    }
}
