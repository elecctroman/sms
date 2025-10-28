<?php
declare(strict_types=1);
$flash = $session->get('flash');
if (is_array($flash)) {
    foreach ($flash as $type => $message) {
        echo '<div class="alert alert-' . $escape($type) . '" role="alert">' . $escape($message) . '</div>';
    }
    $session->forget('flash');
}
