<?php
declare(strict_types=1);

namespace App\Lib;

use App\Core\Config;

final class SimpleMailer
{
    public function send(string $to, string $subject, string $message): bool
    {
        $from = Config::get('mail.from', 'no-reply@example.com');
        $headers = 'From: ' . Config::get('mail.from_name', 'SMS Onay') . ' <' . $from . '>' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=UTF-8';

        return \mail($to, $subject, $message, $headers);
    }
}
