<?php

namespace Tests\Feature;
use App\Courses;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_have_10_users()
    {
        $this->assertEquals(10, Courses::count());
    }
}
