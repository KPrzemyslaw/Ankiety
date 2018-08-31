<?php

namespace KPrzemyslaw;

/**
 * Class KPUtils
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPUtils
{
    /**
     * @param mixed $value
     * @param bool  $htmlentit
     * @param int   $quoteStyle
     * @return array|mixed|string
     */
    static public function checkValue($value, $htmlentit=true, $quoteStyle=ENT_COMPAT) {
        if(is_array($value)) {
            $result = array();
            foreach($value as $k => $v) {
                $result[$k] = self::checkValue($v, $htmlentit, $quoteStyle);
            }
            return $result;
        }
        elseif(!empty($value)) {
            if($htmlentit) {
                $value = htmlspecialchars($value, $quoteStyle);
            }
            else {
                $value = str_replace(['ó','Ó'], ['&oacute;','&Oacute;'], $value);
            }
        }

        return $value;
    }

}
