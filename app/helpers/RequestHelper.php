<?php

class RequestHelper {
    public static function isAjaxRequest() {
        $request = Flight::request();
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';

        return $request->ajax || stripos($acceptHeader, 'application/json') !== false;
    }
}
