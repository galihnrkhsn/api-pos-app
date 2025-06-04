<?php
    function env($key, $default=null){
        static $env;

        if (!$env){
            $lines = file(__DIR__ . '/../.env');
            foreach($lines as $line) {
                if (trim($line) === '' || str_starts_with(trim($line), '#')) continue;
                list($k, $v) = explode('=', trim($line), 2);
                $env[$k] = $v;

            }
        }

        return $env[$key] ?? $default;
    }
?>