<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 19.01.2018
 * Time: 16:06
 */
class Mail
{
    protected function sendMail(string $to, string $subject, string $message)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (!mail($to, $subject, $message, $headers)) {
            $logLine = '- Eine Email konnte nicht versendet werden, überprüfen Sie das error.txt im Ordner /sendmails';
            $this->writeLogFile($logLine);
        }
    }

    protected function writeLogFile(string $logLine)
    {
        $pathFile = 'logs/logs_mail.txt';
        $timestamp = getdate();
        $timestamp = $timestamp['mday'] .'.'. $timestamp['mon'] . '.' . $timestamp['yday'] . ' ' . $timestamp['hours'] . ':' . $timestamp['minutes'] . ':'. $timestamp['seconds'];
        $logLine = $timestamp . '-' . $logLine . "\n";
        file_put_contents($pathFile, $logLine, FILE_APPEND);
    }
}