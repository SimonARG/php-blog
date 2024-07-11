<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Controllers\Controller;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class SearchController extends Controller
{
    protected $post;
    protected $comment;
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->post = new Post();
        $this->comment = new Comment();
        $this->user = new User();
    }

    public function search() : void
    {
        $query = $_GET['query'];

        $post = new Post();

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $post->search($query, $currentPage);

        if(!$result) {
            $this->helpers->setPopup('No hay resultados');

            header('Location: /');
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        $posts = $this->helpers->formatDates($posts);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        $this->helpers->view('posts.results', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }

    public function getUserPosts(int $id) : void
    {
        // Get user name
        $user = $this->user->getUserById($id);

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $this->user->getUserPosts($id, $currentPage);

        if(!$result) {
            $this->helpers->setPopup('No hay resultados');

            header('Location: /');
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        $posts = $this->helpers->formatDates($posts);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        $this->helpers->view('posts.results', [
            'posts' => $posts,
            'user' => $user,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }

    public function saved(int $id) : void
    {
        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $result = $this->user->getSavedPosts($id, $currentPage);

        if(!$result) {
            $this->helpers->setPopup('No hay resultados');

            header('Location: /');
            return;
        }

        $posts = $result['posts'];

        // Get the total number of posts to calculate pagination
        $totalPosts = $result['count'];

        $converter = new GithubFlavoredMarkdownConverter([
        ]);

        $posts = $this->helpers->formatDates($posts);

        foreach ($posts as $key => $post) {
            $convertedContent = $converter->convert($post['body']);
            $posts[$key]['body'] = $convertedContent->getContent();
        }
        
        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        $this->helpers->view('posts.results', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
        ]);
    }
}