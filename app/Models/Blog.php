<?php

namespace App\Models;

use App\Models\Model;

class Blog extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBlogConfig(): array|int
    {
        $sql = "SELECT * FROM config;";
        $result = $this->db->fetch($sql);

        return $result ? $result : 0;
    }

    public function getContacts(): array|int
    {
        $sql = "SELECT * FROM contact;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function getFriends(): array|int
    {
        $sql = "SELECT * FROM friends;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function getLinks(): array|int
    {
        $sql = "SELECT * FROM links;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function updateTitle(string $newTitle): array|int
    {
        $sql = "UPDATE config SET title = :title;";
        $result = $this->db->query($sql, [":title" => $newTitle]);

        return $result ? 1 : 0;
    }

    public function updateBgImage(string $newBgImg): array|int
    {
        $sql = "UPDATE config SET bg_image = :bg_image;";
        $result = $this->db->query($sql, [":bg_image" => $newBgImg]);

        $sql = "UPDATE config SET bg_color = NULL;";
        $this->db->query($sql);

        return $result ? 1 : 0;
    }

    public function updateIcon(string $newIcon): array|int
    {
        $sql = "UPDATE config SET icon = :icon;";
        $result = $this->db->query($sql, [":icon" => $newIcon]);

        return $result ? 1 : 0;
    }

    public function updateBgColor(string $newBgColor): array|int
    {
        $sql = "UPDATE config SET bg_color = :bg_color;";
        $result = $this->db->query($sql, [":bg_color" => $newBgColor]);

        $sql = "UPDATE config SET bg_image = NULL;";
        $this->db->query($sql);

        return $result ? 1 : 0;
    }

    public function updateTextColor(string $newTextColor): array|int
    {
        $sql = "UPDATE config SET text_color = :text_color;";
        $result = $this->db->query($sql, [":text_color" => $newTextColor]);

        return $result ? 1 : 0;
    }

    public function updateTextDim(string $newTextColor): array|int
    {
        $sql = "UPDATE config SET text_dim = :text_dim;";
        $result = $this->db->query($sql, [":text_dim" => $newTextColor]);

        return $result ? 1 : 0;
    }

    public function updatePanelBg(string $newBgColor): array|int
    {
        $sql = "UPDATE config SET panel_color = :panel_color;";
        $result = $this->db->query($sql, [":panel_color" => $newBgColor]);

        return $result ? 1 : 0;
    }

    public function updatePanelHover(string $newHoverColor): array|int
    {
        $sql = "UPDATE config SET panel_hover = :panel_hover;";
        $result = $this->db->query($sql, [":panel_hover" => $newHoverColor]);

        return $result ? 1 : 0;
    }

    public function updatePanelActive(string $newActiveColor): array|int
    {
        $sql = "UPDATE config SET panel_active = :panel_active;";
        $result = $this->db->query($sql, [":panel_active" => $newActiveColor]);

        return $result ? 1 : 0;
    }

    public function updateAbout(string $about): array|int
    {
        $sql = "UPDATE config SET info = :info;";
        $result = $this->db->query($sql, [":info" => $about]);

        return $result ? 1 : 0;
    }

    public function updateMainScrollbarColor(string $color): bool
    {
        $sql = "UPDATE config SET main_scrollbar = :color;";
        $result = $this->db->query($sql, [":color" => $color]);

        return $result ? true : false;
    }

    public function updateInputScrollbarColor(string $color): bool
    {
        $sql = "UPDATE config SET input_scrollbar = :color;";
        $result = $this->db->query($sql, [":color" => $color]);

        return $result ? true : false;
    }

    public function updatePopupBgColor(string $color): bool
    {
        $sql = "UPDATE config SET popup = :color;";
        $result = $this->db->query($sql, [":color" => $color]);

        return $result ? true : false;
    }
}
