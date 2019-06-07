<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
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
        $thread = make(Thread::class); //must be created to persist ID

        $response = $this->post('/threads', $thread->toArray());

        // We should see the new thread
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
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

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make(Thread::class, $overrides);
        return $this->post('/threads', $thread->toArray());
    }

}
