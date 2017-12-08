<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 14:05
 */

if(isset($_SESSION['loggedin']) && !empty($_SESSION['loggedin'])) {
    echo "Hi " . $_SESSION['username'];
}

echo "Hi";