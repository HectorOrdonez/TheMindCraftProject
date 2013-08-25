<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Date: 23/07/13 14:00
 */

// Base path of the website.
if (_PRODUCTION === TRUE) {
    define ('DB_TYPE','mysql');
    define ('DB_HOST','localhost');
    define ('DB_NAME','themind_main');
    define ('DB_USER','themind_master');
    define ('DB_PASS','M1ndW0rd');
} else {
    define ('DB_TYPE','mysql');
    define ('DB_HOST','localhost');
    define ('DB_NAME','themindcraftproject');
    define ('DB_USER','root');
    define ('DB_PASS','');
}