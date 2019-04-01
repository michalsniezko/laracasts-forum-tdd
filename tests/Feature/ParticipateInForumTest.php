<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        // GIVEN we have an authenticated user
        $this->be($user = create(User::class));

        // AND existing thread
        $thread = create(Thread::class);

        // WHEN a user adds a reply
        $reply = create(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());

        // THEN their reply should be visible on the page
        $this->get($thread->path())->assertSee($reply->body);
    }
}
