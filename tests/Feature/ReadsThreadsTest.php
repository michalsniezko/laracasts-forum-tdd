<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadsThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @var null | Thread $thread */
    private $thread = null;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);

    }

    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $response = $this->get('/threads/' . $this->thread->id)->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        /**
         * GIVEN we have a thread
         * AND that thread includes replies
         * WHEN we visit a thread page
         * THEN we should see the replies
         */
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $this->get('/threads/' . $this->thread->id)->assertSee($reply->body);

    }
}