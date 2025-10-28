<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\AnnouncementRepository;
use App\Repositories\BlogRepository;
use App\Repositories\ServiceRepository;

final class HomeController extends Controller
{
    private ServiceRepository $services;
    private BlogRepository $blog;
    private AnnouncementRepository $announcements;
    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->services = new ServiceRepository();
        $this->blog = new BlogRepository();
        $this->announcements = new AnnouncementRepository();
    }

    public function index(): Response
    {
        $services = $this->services->allEnabled();
        $posts = $this->blog->latest();
        $announcements = $this->announcements->latest();

        return $this->render('site/home', [
            'services' => $services,
            'posts' => $posts,
            'announcements' => $announcements,
        ]);
    }

    public function faq(): Response
    {
        return $this->render('site/faq');
    }

    public function announcements(): Response
    {
        return $this->render('site/announcements', [
            'announcements' => $this->announcements->latest(20),
        ]);
    }

    public function blog(): Response
    {
        return $this->render('site/blog_list', [
            'posts' => $this->blog->latest(20),
        ]);
    }

    public function blogDetail(array $request): Response
    {
        $slug = (string) ($request['params']['slug'] ?? '');
        $post = $this->blog->findBySlug($slug);
        if ($post === null) {
            return new Response('Yazı bulunamadı', 404);
        }

        return $this->render('site/blog_detail', ['post' => $post]);
    }
}
