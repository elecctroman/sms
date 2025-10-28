<?php
declare(strict_types=1);

namespace App\Lib;

use App\Core\Config;

final class SimpleMailer
{
    private ?string $lastError = null;

    public function send(string $to, string $subject, string $message): bool
    {
        $this->lastError = null;

        $from = (string) Config::get('mail.from', 'no-reply@example.com');
        $fromName = (string) Config::get('mail.from_name', 'SMS Onay');

        $baseHeaders = [
            'From: ' . $fromName . ' <' . $from . '>',
            'Reply-To: ' . $from,
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Date: ' . gmdate('D, d M Y H:i:s') . ' +0000',
            'X-Mailer: SMS Onay Platformu',
        ];

        $headers = implode("\r\n", $baseHeaders);
        $smtpHeaders = $headers . "\r\nTo: <" . $to . '>\r\nSubject: ' . $this->encodeSubject($subject);

        $subjectLine = $this->encodeSubject($subject);

        $driver = (string) Config::get('mail.driver', 'mail');

        if ($driver === 'smtp') {
            return $this->sendViaSmtp($to, $message, $smtpHeaders, $from);
        }

        if (!function_exists('mail')) {
            $this->lastError = 'Sunucuda mail() fonksiyonu devre dışı bırakılmış. Lütfen SMTP ayarlarını config/config.php dosyasından yapılandırın.';
            $this->log($this->lastError);

            return false;
        }

        $sent = \mail($to, $subjectLine, $message, $headers);
        if ($sent === false) {
            $this->lastError = 'PHP mail() fonksiyonu e-postayı gönderemedi. Paylaşımlı hostinginizde mail() desteğini veya SMTP ayarlarını kontrol edin.';
            $this->log($this->lastError);
        }

        return $sent;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function sendViaSmtp(string $to, string $message, string $headers, string $from): bool
    {
        $host = (string) Config::get('mail.smtp.host', '');
        if ($host === '') {
            $this->lastError = 'SMTP sunucusu yapılandırması eksik. config/config.php içinde mail.smtp.host değerini girin.';
            $this->log($this->lastError);

            return false;
        }

        $port = (int) Config::get('mail.smtp.port', 587);
        $encryption = strtolower((string) Config::get('mail.smtp.encryption', ''));
        $username = (string) Config::get('mail.smtp.username', '');
        $password = (string) Config::get('mail.smtp.password', '');
        $timeout = 15;

        $transportPrefix = '';
        if ($encryption === 'ssl') {
            $transportPrefix = 'ssl://';
        } elseif ($encryption === 'tls') {
            $transportPrefix = 'tls://';
        }

        $socket = @stream_socket_client($transportPrefix . $host . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        if (!is_resource($socket)) {
            $this->lastError = 'SMTP sunucusuna bağlanılamadı: ' . $errstr . ' (' . $errno . ')';
            $this->log($this->lastError);

            return false;
        }

        stream_set_timeout($socket, $timeout);

        if (!$this->expectResponse($socket, ['220'])) {
            fclose($socket);

            return false;
        }

        $helloDomain = $this->determineHelloDomain();
        $this->writeLine($socket, 'EHLO ' . $helloDomain);
        if (!$this->expectResponse($socket, ['250'])) {
            fclose($socket);

            return false;
        }

        if ($username !== '') {
            $this->writeLine($socket, 'AUTH LOGIN');
            if (!$this->expectResponse($socket, ['334'])) {
                fclose($socket);

                return false;
            }

            $this->writeLine($socket, base64_encode($username));
            if (!$this->expectResponse($socket, ['334'])) {
                fclose($socket);

                return false;
            }

            $this->writeLine($socket, base64_encode($password));
            if (!$this->expectResponse($socket, ['235'])) {
                fclose($socket);

                return false;
            }
        }

        $this->writeLine($socket, 'MAIL FROM: <' . $from . '>');
        if (!$this->expectResponse($socket, ['250'])) {
            fclose($socket);

            return false;
        }

        $this->writeLine($socket, 'RCPT TO: <' . $to . '>');
        if (!$this->expectResponse($socket, ['250', '251'])) {
            fclose($socket);

            return false;
        }

        $this->writeLine($socket, 'DATA');
        if (!$this->expectResponse($socket, ['354'])) {
            fclose($socket);

            return false;
        }

        $normalizedHeaders = preg_replace("#\r?\n#", "\r\n", trim($headers));
        $normalizedMessage = preg_replace("#\r?\n#", "\r\n", $message);
        $payload = $normalizedHeaders . "\r\n\r\n" . $this->dotStuff($normalizedMessage);

        $this->writeLine($socket, $payload);
        $this->writeLine($socket, '.');
        if (!$this->expectResponse($socket, ['250'])) {
            fclose($socket);

            return false;
        }

        $this->writeLine($socket, 'QUIT');
        $this->expectResponse($socket, ['221']);
        fclose($socket);

        return true;
    }

    private function expectResponse($socket, array $expected): bool
    {
        $response = $this->readResponse($socket);
        if ($response === '') {
            $this->lastError = 'SMTP sunucusundan yanıt alınamadı.';
            $this->log($this->lastError);

            return false;
        }

        $code = substr($response, 0, 3);
        if (!in_array($code, $expected, true)) {
            $this->lastError = 'SMTP hatası: ' . trim($response);
            $this->log($this->lastError);

            return false;
        }

        return true;
    }

    private function readResponse($socket): string
    {
        $data = '';
        while (($line = fgets($socket, 515)) !== false) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }

        return $data;
    }

    private function writeLine($socket, string $line): void
    {
        fwrite($socket, $line . "\r\n");
    }

    private function dotStuff(string $message): string
    {
        $lines = explode("\r\n", $message);
        foreach ($lines as &$line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
        }

        return implode("\r\n", $lines);
    }

    private function determineHelloDomain(): string
    {
        $baseUrl = (string) Config::get('app.base_url', 'https://localhost');
        $host = parse_url($baseUrl, PHP_URL_HOST);

        if (is_string($host) && $host !== '') {
            return $host;
        }

        return 'localhost';
    }

    private function log(string $message): void
    {
        error_log('[SimpleMailer] ' . $message);
    }

    private function encodeSubject(string $subject): string
    {
        if (function_exists('mb_encode_mimeheader')) {
            return mb_encode_mimeheader($subject, 'UTF-8', 'B', "\r\n");
        }

        return '=?UTF-8?B?' . base64_encode($subject) . '?=';
    }
}
