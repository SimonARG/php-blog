<?php

namespace App\Controllers;

use App\Models\Post;

class PostController
{
    public function index()
    {
        $postModel = new Post();

        // Get the current page from the query parameters, default to 1 if not set
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get the posts for the current page
        $posts = $postModel->getPosts($currentPage);

        // Get the total number of posts to calculate pagination
        $totalPosts = $postModel->getAllPosts();

        $postsPerPage = $GLOBALS['config']['posts_per_page'];

        // Calculate the total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Pass the necessary data to the view
        return view('posts.index', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function show($id)
    {
        $postModel = new Post();

        // Get the posts for the current page
        $post = $postModel->getPost($id);

        return view('posts.single', [
            'post' => $post
        ]);
    }
}