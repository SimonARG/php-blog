<?php

namespace App\Controllers;

use DateTime;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Helpers\Helpers;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class SearchController
{
    protected $baseUrl;
    protected $postModel;
    protected $commentModel;
    protected $userModel;
    protected $helpers;

    public function __construct()
    {
        $this->baseUrl = $GLOBALS['config']['base_url'];
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->userModel = new User();
        $this->helpers = new Helpers();
    }

    public function search()
    {
        $query = $_GET['query'];

        $postModel = new Post();

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $postModel->search($query, $currentPage);

        if(!$result) {
            $this->helpers->setPopup('No hay resultados');

            return header('Location: /');
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();

            $postDate = new DateTime($post['created_at']);
            $postStrdate = $postDate->format('Y/m/d H:i');
            $posts[$key]['created_at'] = $postStrdate;
    
            if (isset($post['updated_at'])) {
                $postUpDate = new DateTime($post['updated_at']);
                $postUpStrdate = $postUpDate->format('Y/m/d H:i');
                $posts[$key]['updated_at'] = $postUpStrdate;
            }
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        return $this->helpers->view('posts.results', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }

    public function getUserPosts($id)
    {
        // Get user name
        $user = $this->userModel->getUserById($id);

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $this->userModel->getUserPosts($id, $currentPage);

        if(!$result) {
            $this->helpers->setPopup('No hay resultados');

            return header('Location: /');
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();

            $postDate = new DateTime($post['created_at']);
            $postStrdate = $postDate->format('Y/m/d H:i');
            $posts[$key]['created_at'] = $postStrdate;
    
            if (isset($post['updated_at'])) {
                $postUpDate = new DateTime($post['updated_at']);
                $postUpStrdate = $postUpDate->format('Y/m/d H:i');
                $posts[$key]['updated_at'] = $postUpStrdate;
            }
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        return $this->helpers->view('posts.results', [
            'posts' => $posts,
            'user' => $user,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }

    public function saved($id)
    {
        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $this->userModel->getSavedPosts($id, $currentPage);

        if(!$result) {
            $$this->helpers->setPopup('No hay resultados');

            return header('Location: /');
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();

            $postDate = new DateTime($post['created_at']);
            $postStrdate = $postDate->format('Y/m/d H:i');
            $posts[$key]['created_at'] = $postStrdate;
    
            if (isset($post['updated_at'])) {
                $postUpDate = new DateTime($post['updated_at']);
                $postUpStrdate = $postUpDate->format('Y/m/d H:i');
                $posts[$key]['updated_at'] = $postUpStrdate;
            }
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        return $this->helpers->view('posts.results', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }
}