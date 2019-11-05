<?php

class lise_datetime_utils {

    private static $_timepicker2php = array(
        'hh' => 'h',
        'HH' => 'H',
        'mm' => 'i',
        'ss' => 's',
        'p' => 'A',
        'H' => 'G',
        'h' => 'g',
    );
    private static $_datepicker2php = array(
        'ATOM' => 'Y-m-d',
        'COOKIE' => 'D, d M Y',
        'ISO_8601' => 'Y-m-d',
        'RFC_822' => 'D, j M y',
        'RFC_850' => 'l, d-M-y',
        'RFC_1036' => 'D, j M y',
        'RFC_1123' => 'D, j M Y',
        'RFC_2822' => 'D, j M Y',
        'RSS' => 'D, j M y',
        'TICKS' => '<!>', // replace with Windows ticks
        'TIMESTAMP' => 'U',
        'W3C' => 'Y-m-d',
        'dd' => 'd',
        'oo' => '<z>', // pad to three digits
        'DD' => 'l',
        'mm' => 'm',
        'MM' => 'F',
        'yy' => 'Y',
        '@' => 'U',
        '!' => '<!>', // replace with Windows ticks
        'd' => 'j',
        'o' => 'z',
        'D' => 'D',
        'm' => 'n',
        'M' => 'M',
        'y' => 'y',
    );

    private static function _format_replace($format, $translations) {
        $pattern = join('|', array_map('preg_quote', array_keys($translations)));

        preg_match_all("/$pattern/s", $format, $matches);

        $processed = array();

        foreach ($matches[0] as $match) {
            if (!isset($processed[$match])) {
                $format = str_replace($match, $translations[$match], $format);
                $processed[$match] = true;
            }
        }

        return $format;
    }

    public static final function date_to_unix_ts($value) {
        # fix to take into account timezone offsets as suggested by
        # Darren Kierman (kierweb)
        $offset = date_format(date_create($value), 'Z');
        return date_format(date_create($value), 'U') + $offset;
    }

    public static final function unix_ts_to_date($value, $format) {
        $datetime = new DateTime("@$value");
        return $datetime->format($format);
    }

    public static function format_to_datepicker($format) {
        return self::_format_replace($format, self::$_datepicker2php);
    }

    public static function format_to_timepicker($format) {
        return self::_format_replace($format, self::$_timepicker2php);
    }

}