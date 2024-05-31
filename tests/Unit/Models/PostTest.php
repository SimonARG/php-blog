<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Post;
use Mockery as Mockery;

class PostTest extends TestCase
{
    protected $dbMock;
    protected $post;

    protected function setUp(): void
    {
        $this->dbMock = Mockery::mock('db');
        $GLOBALS['db'] = $this->dbMock;
        $GLOBALS['config']['posts_per_page'] = 6;

        $this->post = new Post();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetPosts()
    {
        $samplePosts = [
            [
                'id' => 1,
                'title' => 'Sample Post 1',
                'subtitle' => 'Subtitle 1',
                'thumb' => 'thumb1.avif',
                'body' => 'Body of sample post 1',
                'user_id' => 1,
                'created_at' => '2024-05-27 14:39:37',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'title' => 'Sample Post 2',
                'subtitle' => 'Subtitle 2',
                'thumb' => 'thumb2.avif',
                'body' => 'Body of sample post 2',
                'user_id' => 2,
                'created_at' => '2024-05-28 14:39:37',
                'updated_at' => '2024-05-29 14:39:37',
                'deleted_at' => null
            ]
        ];

        $this->dbMock->shouldReceive('fetchAll')
                     ->once()
                     ->with('SELECT * FROM posts LIMIT :limit OFFSET :offset', [
                         ':limit' => 6,
                         ':offset' => 0
                     ], [
                         ':limit' => \PDO::PARAM_INT,
                         ':offset' => \PDO::PARAM_INT
                     ])
                     ->andReturn($samplePosts);

        $result = $this->post->getPosts(1);

        $this->assertEquals($samplePosts, $result);
    }

    public function testGetAllPosts()
    {
        $this->dbMock->shouldReceive('fetch')
                     ->once()
                     ->with('SELECT COUNT(*) FROM posts')
                     ->andReturn(['COUNT(*)' => 2]);

        $result = $this->post->getAllPosts();

        $this->assertEquals(2, $result);
    }
}

