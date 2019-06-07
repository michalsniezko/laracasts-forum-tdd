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

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);
        /** @var Reply $reply */
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

}
