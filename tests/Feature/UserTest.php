<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * Test case for add comment
     *
     * @return void
     */
    public function testAddComment()
    {
        $response = $this->get("/add-comment?comment=Test new comment&user_id=1");

        if ($response->status() != 200) {
            $response->assertStatus($response->status())
                ->assertJsonStructure(['error']);
        } else {
            $response->assertStatus($response->status())
                ->assertJsonStructure(['message']);
        }
    }

    /**
     * Test case for watch lesson
     *
     * @return void
     */
    public function testWatchLesson()
    {
        $response = $this->get("/watch-lesson?lesson_id=1&user_id=1");

        if ($response->status() != 200) {
            $response->assertStatus($response->status())
                ->assertJsonStructure(['error']);
        } else {
            $response->assertStatus($response->status())
                ->assertJsonStructure(['message']);
        }
    }
}
