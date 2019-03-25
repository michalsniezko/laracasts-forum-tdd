<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_authenticated_user_can_create_form_threads()
    {
        // Given we have a signed in user
        $this->actingAs(factory(User::class)->create());

        // When we hit the endpoint to create a new thread
        /** @var Thread $thread */
        $thread = factory(Thread::class)->make(); // or raw()
        $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread page
        $response = $this->get($thread->path());

        // We should see the new thread
        $response
            ->assertSee($thread->getAttributeValue('title'))
            ->assertSee($thread->getAttributeValue('body'));
    }

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);

        $rawThread = factory(Thread::class)->raw();
        $this->post('/threads', $rawThread);
    }
}
