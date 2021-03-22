<?php

/* Allow Core
 * DESCRIPTION: Role/scope validation
 * Create a new scope by defining a static constant array
 * i.e, private static $DEMO = array('demo-scope');
 * note that every scope need to be available on the validation of the token
 */

namespace Educore\EducoreAuth\Helpers;

use Geekcow\FonyCore\Helpers\AllowCore;

class Allow extends AllowCore
{
    private static $ADMINISTRATOR = array('administrator');
    private static $VISITOR = array('visitor');
    private static $MANAGEUSER = array('visitor', 'administrator');
    private static $SYSTEM = array('system', 'administrator');

    public static function ADMINISTRATOR()
    {
        return self::$ADMINISTRATOR;
    }

    public static function VISITOR()
    {
        return self::$VISITOR;
    }

    public static function SYSTEM()
    {
        return self::$SYSTEM;
    }

    public static function MANAGEUSER()
    {
        return self::$MANAGEUSER;
    }
}
