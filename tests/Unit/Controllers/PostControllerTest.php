<?php

use PHPUnit\Framework\TestCase;
use Mockery as m;
use App\Controllers\PostController;
use App\Models\Post;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class PostControllerTest extends TestCase
{
    protected $postModel;
    protected $dbMock;
    protected $controller;

    protected function setUp(): void
    {
        // Set up the global config values
        $GLOBALS['config'] = [
            'base_url' => 'http://localhost',
            'posts_per_page' => 10
        ];

        // Mock the Post model
        $this->postModel = m::mock(Post::class);

        // Mock the database instance
        $this->dbMock = m::mock('stdClass');
        
        // Set the mocked database instance in the global scope
        $GLOBALS['db'] = $this->dbMock;

        // Create an instance of PostController
        $this->controller = new PostController();

        // Use reflection to set the protected postModel property
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('postModel');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->postModel);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testIndex()
    {
        // Mock the $_GET global to simulate query parameters
        $_GET['page'] = 1;

        // Set up mock return values for the Post model methods
        $posts = [
            [
                'id' => 1,
                'title' => 'title',
                'subtitle' => 'subtitle',
                'thumb' => 'thumb',
                'body' => 'body',
                'user_id' => 1,
                'created_at' => '2024-05-31 16:48:17',
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'username' => 'testuser'
            ]
        ];

        $totalPosts = 1;

        // Set up mock return values for the database interactions
        $this->dbMock->shouldReceive('fetchAll')
            ->andReturn($posts);

        $this->dbMock->shouldReceive('fetch')
            ->andReturn(['COUNT(*)' => $totalPosts]);

        // Ensure the Post model methods return the expected data
        $this->postModel->shouldReceive('getPosts')
            ->with(1)
            ->andReturn($posts);

        $this->postModel->shouldReceive('getAllPosts')
            ->andReturn($totalPosts);

        // Simulate the view function
        if (!function_exists('view')) {
            function view($viewName, $data = [])
            {
                return $data;
            }
        }

        // Execute the index method
        $result = $this->controller->index();

        // Create an instance of GithubFlavoredMarkdownConverter
        $converter = new GithubFlavoredMarkdownConverter([]);
        $convertedContent = $converter->convert('body')->getContent();

        // Assert the results
        $this->assertEquals([
            'posts' => [
                [
                    'id' => 1,
                    'title' => 'title',
                    'subtitle' => 'subtitle',
                    'thumb' => 'thumb',
                    'body' => $convertedContent,
                    'user_id' => 1,
                    'created_at' => '2024-05-31 16:48:17',
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                    'username' => 'testuser'
                ]
            ],
            'currentPage' => 1,
            'totalPages' => 1
        ], $result);
    }

    public function testShow()
    {
        // Set up mock return value for the Post model method
        $post = [
            'id' => 1,
            'title' => 'title',
            'subtitle' => 'subtitle',
            'thumb' => 'thumb',
            'body' => 'body',
            'user_id' => 1,
            'created_at' => '2024-05-31 16:48:17',
            'updated_at' => NULL,
            'deleted_at' => NULL,
            'username' => 'testuser'
        ];

        // Ensure the getPostById method returns the expected data
        $this->postModel->shouldReceive('getPostById')
            ->with(1)
            ->andReturn($post);

        // Simulate the view function
        if (!function_exists('view')) {
            function view($viewName, $data = [])
            {
                return $data;
            }
        }

        // Execute the show method
        $result = $this->controller->show(1);

        // Create an instance of GithubFlavoredMarkdownConverter
        $converter = new GithubFlavoredMarkdownConverter([]);
        $convertedContent = $converter->convert('body')->getContent();

        // Assert the results
        $this->assertEquals([
            'post' => [
                'id' => 1,
                'title' => 'title',
                'subtitle' => 'subtitle',
                'thumb' => 'thumb',
                'body' => $convertedContent,
                'user_id' => 1,
                'created_at' => '2024-05-31 16:48:17',
                'updated_at' => NULL,
                'deleted_at' => NULL,
                'username' => 'testuser'
            ]
        ], $result);
    }
}
