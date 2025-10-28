<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Lib\Validation;
use App\Repositories\TicketMessageRepository;
use App\Repositories\TicketRepository;

final class SupportController extends Controller
{
    private TicketRepository $tickets;
    private TicketMessageRepository $messages;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->tickets = new TicketRepository();
        $this->messages = new TicketMessageRepository();
    }

    public function index(): Response
    {
        $user = $this->session->get('user');
        return $this->render('site/support_index', [
            'tickets' => $this->tickets->byUser((int) $user['id']),
            'csrf_token' => $this->csrf->token('ticket_create'),
        ]);
    }

    public function store(array $request): Response
    {
        if (!$this->csrf->validate('ticket_create', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $data = $request['body'];
        $errors = Validation::validate($data, [
            'subject' => ['required'],
            'message' => ['required'],
        ]);

        if ($errors !== []) {
            $user = $this->session->get('user');
            return $this->render('site/support_index', [
                'tickets' => $this->tickets->byUser((int) $user['id']),
                'errors' => $errors,
                'csrf_token' => $this->csrf->token('ticket_create'),
            ]);
        }

        $user = $this->session->get('user');
        $ticketId = $this->tickets->create([
            'user_id' => (int) $user['id'],
            'subject' => (string) $data['subject'],
            'status' => 'open',
            'priority' => 'normal',
        ]);

        $this->messages->create([
            'ticket_id' => $ticketId,
            'user_id' => (int) $user['id'],
            'message' => (string) $data['message'],
            'attachments' => [],
        ]);

        return $this->redirect('/support');
    }
}
