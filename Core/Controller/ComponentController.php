<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 16:00
 */


abstract class ComponentController
{
    abstract protected function index();

    protected function output(string $controllerName, string $methodName, array $placeholders = array())
    {
        if (empty($_SESSION['loggedin'])) {
            $linkHref = '/Access/';
            $linkText = 'Login/ Registrieren';
        } else {
            $linkHref = '/Access/logout';
            $linkText = 'Logout';
        }
        $headerPlaceholders = array(
            array(
                'name' => 'LOGIN',
                'template' => 'loginLink',
                'type' => false,
                'innerPlaceholders' => array(
                    'LINKHREF',
                    'LINKTEXT',
                ),
                'placeholderContent' => array(
                    'linkhref' => $linkHref,
                    'linktext' => $linkText,
                ),
            ),
        );

        new View($controllerName,$methodName, $placeholders, $headerPlaceholders, array());
    }

}