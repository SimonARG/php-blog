<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Services\BlogService;
use App\Controllers\Controller;

class SettingController extends Controller
{
    protected $blog;
    protected $service;

    public function __construct()
    {
        parent::__construct();

        $this->blog = new Blog();
        $this->service = new BlogService();
    }

    public function about(): void
    {
        $blogInfoRaw = $this->blog->getBlogConfig()['info'];

        $this->helpers->view('blog.about', ['blogInfoRaw' => $blogInfoRaw]);

        return;
    }

    public function updateAbout(array $request): void
    {
        $about = $request['about'];

        $result = $this->blog->updateAbout($about);

        $this->helpers->setPopup('Informacion actualizada');

        header('Location: /about');

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
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newTitle = $request['title'];

        $this->blog->updateTitle($newTitle);

        header('Location: /admin/settings');

        return;
    }

    public function updateBgColor(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newBgColor = $request['bg-color'];

        $this->blog->updateBgColor($newBgColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateTextColor(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newTextColor = $request['text-color'];

        $this->blog->updateTextColor($newTextColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateTextDim(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newTextDim = $request['text-dim'];

        $this->blog->updateTextDim($newTextDim);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelBgColor(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newPanelBgColor = $request['panel-color'];

        $this->blog->updatePanelBg($newPanelBgColor);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelHoverColor(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newHoverColor = $request['panel-hover'];

        $this->blog->updatePanelHover($newHoverColor);

        header('Location: /admin/settings');

        return;
    }

    public function updatePanelActiveColor(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

        $newActiveColor = $request['panel-active'];

        $this->blog->updatePanelActive($newActiveColor);

        header('Location: /admin/settings');

        return;
    }

    public function updateBgImage(array $request) : void
    {
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

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
        if (!$this->security->isElevatedUser()) {
            if ($this->security->verifySession()) {
                $this->helpers->setPopup('Operacion no autorizada');
            }

            header('Location: /');

            return;
        }

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