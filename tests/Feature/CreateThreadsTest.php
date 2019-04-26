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
    function an_authenticated_user_can_create_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
        /** @var Thread $thread */
        $thread = create(Thread::class); //must be created to persist ID
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
        // Given exception handling is on:
        $this->withExceptionHandling();

        // When I try to visit thread creation page without being authenticated:
        $this->get('threads/create')
            // Then I get redirected to login page:
            ->assertRedirect('/login');

        // When I try to manually post a thread:
        $this->post('threads')
            // Then I get redirected to login page:
            ->assertRedirect('/login');
    }
}
