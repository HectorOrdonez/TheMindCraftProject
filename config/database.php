<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Date: 11/06/13 13:46
 */

// Base path of the website.
if (_PRODUCTION === TRUE) {
    define ('DB_TYPE','mysql');
    define ('DB_HOST','localhost');
    define ('DB_NAME','hecnel');
    define ('DB_USER','root');
    define ('DB_PASS','');
} else {
    define ('DB_TYPE','mysql');
    define ('DB_HOST','localhost');
    define ('DB_NAME','selfology');
    define ('DB_USER','root');
    define ('DB_PASS','');
}