<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Blog;
use App\Services\BlogService;

class BlogController extends Controller
{
    protected $blog;
    protected $service;

    public function __construct()
    {
        parent::__construct();

        $this->blog = new Blog();
        $this->service = new BlogService();
    }

    public function contact(): void
    {
        $contacts = $this->blog->getContacts();

        if (!$contacts) {
            $this->helpers->view('blog.contact');

            return;
        }

        $this->helpers->view('blog.contact', ['contacts' => $contacts]);

        return;
    }

    public function friends(): void
    {
        $friends = $this->blog->getFriends();

        if (!$friends) {
            $this->helpers->view('blog.friends');

            return;
        }

        $this->helpers->view('blog.friends', ['friends' => $friends]);

        return;
    }

    public function links(): void
    {
        $links = $this->blog->getLinks();

        if (!$links) {
            $this->helpers->view('blog.links');

            return;
        }

        $this->helpers->view('blog.links', ['links' => $links]);

        return;
    }

    public function about(): void
    {
        $this->helpers->view('blog.about');

        return;
    }

    public function settings(): void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $this->helpers->view('admin.settings');

        return;
    }

    public function updateTitle(array $request) : void
    {
        $newTitle = $request['title'];

        $this->blog->updateTitle($newTitle);

        header('Location: /admin/settings');

        return;
    }

    public function updateBgColor(array $request) : void
    {
        $newBgColor = $request['bg-color'];

        $this->blog->updateBgColor($newBgColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateTextColor(array $request) : void
    {
        $newTextColor = $request['text-color'];

        $this->blog->updateTextColor($newTextColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateTextDim(array $request) : void
    {
        $newTextDim = $request['text-dim'];

        $this->blog->updateTextDim($newTextDim);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelBgColor(array $request) : void
    {
        $newPanelBgColor = $request['panel-color'];

        $this->blog->updatePanelBg($newPanelBgColor);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelHoverColor(array $request) : void
    {
        $newHoverColor = $request['panel-hover'];

        $this->blog->updatePanelHover($newHoverColor);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelActiveColor(array $request) : void
    {
        $newActiveColor = $request['panel-active'];

        $this->blog->updatePanelActive($newActiveColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateBgImage(array $request) : void
    {
        $newBgImg = null;
        $errors = [];

        if (isset($_FILES["bg-image"]) && $_FILES["bg-image"]["error"] != UPLOAD_ERR_NO_FILE) {
            $result = $this->service->handleImg($_FILES["bg-image"]);
            $newBgImg = $result['new_img_name'];
            $errors = $result['errors'];
        } elseif (isset($request['previous_thumb'])) {
            $newBgImg = basename($request['previous_thumb']);
        }

        $newBgImg = $newBgImg . '2.webp';

        $this->blog->updateBgImage($newBgImg);

        header('Location: /admin/settings');

        return;
    }

    public function updateIcon(array $request) : void
    {
        $newIcon = null;
        $errors = [];

        if (isset($_FILES["icon"]) && $_FILES["icon"]["error"] != UPLOAD_ERR_NO_FILE) {
            $result = $this->service->handleImg($_FILES["icon"]);

            $newIcon = $result['new_img_name'];
            $errors = $result['errors'];
        } elseif (isset($request['previous_thumb'])) {
            $newIcon = basename($request['previous_thumb']);
        } else {
            $newIcon = 'favicon.png';

            $this->blog->updateIcon($newIcon);
    
            header('Location: /admin/settings');

            return;
        }

        $newIcon = $newIcon . '2.webp';

        $this->blog->updateIcon($newIcon);

        header('Location: /admin/settings');

        return;
    }
}