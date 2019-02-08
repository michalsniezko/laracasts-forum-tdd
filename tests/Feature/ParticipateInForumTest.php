<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        // GIVEN we have an authenticated user
        $this->be($user = factory(User::class)->create());

        // AND existing thread
        $thread = factory(Thread::class)->create();

        // WHEN a user adds a reply
        $reply = factory(Reply::class)->create();
        $this->post($thread->path() . '/replies', $reply->toArray());

        // THEN their reply should be visible on the page
        $this->get($thread->path())->assertSee($reply->body);
    }


}
