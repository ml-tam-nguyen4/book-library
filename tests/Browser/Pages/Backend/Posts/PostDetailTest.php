<?php

namespace Tests\Browser\Pages\Backend\Posts;

use DB;
use App\Model\User;
use App\Model\Book;
use App\Model\Post;
use App\Model\Rating;
use App\Model\Donator;
use App\Model\Comment;
use App\Model\Category;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostDetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;
    // Data general
    private $data;

    /**
     * Override function setUp()
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->user = $this->makeUserLogin();
        $this->data = $this->getData();
    }

    /**
     * Looking button detail enough trasfer to detail post
     *
     * @return void
     */
    public function testSeeButtonShowDetail()
    {
        $this->makeData(10);
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts')
                ->assertSee('List Posts')
                ->assertSee('Detail')
                ->assertVisible('.btn.btn-success');
        });
    }

    /**
     * click button trasfer to detail post has comment
     *
     * @return void
     */
    public function testClickLinkTransfertToDetailHasComment()
    {
        $this->makeData(2);
        $post = Post::first();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts/' . $post->id)
                ->assertPathIs('/admin/posts/' . $post->id)
                ->assertSee('Detail Post')
                ->assertSee('Date')
                ->assertVisible('.post-image[src="' . $post->image_url . '"]');
            $this->assertTrue($browser->text('.post-username') == $post->users->name);
            $this->assertTrue($browser->text('.post-date') == date('d-m-Y', strtotime($post->created_at)));
            $this->assertTrue($browser->text('.post-content') == $post->content);
        });
    }

    /**
     * click button trasfer to detail post has not comment
     *
     * @return void
     */
    public function testClickLinkTransfertToDetailHasNotComment()
    {
        $this->makeDataNotComment(2);
        $post = Post::first();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts/' . $post->id)
                ->assertPathIs('/admin/posts/' . $post->id)
                ->assertSee('Detail Post')
                ->assertSee('Date')
                ->assertDontSee('Comments')
                ->assertVisible('.post-image[src="' . $post->image_url . '"]');
            $this->assertTrue($browser->text('.post-username') == $post->users->name);
            $this->assertTrue($browser->text('.post-date') == date('d-m-Y', strtotime($post->created_at)));
            $this->assertTrue($browser->text('.post-content') == $post->content);
        });
    }

    /**
     * Detail post is review
     *
     * @return void
     */
    public function testClickLinkTransfertToDetaiIsReviewlHaveComment()
    {
        $this->makeDataReview(2);
        $post = Post::first();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts/' . $post->id)
                ->assertPathIs('/admin/posts/' . $post->id)
                ->assertSee('Detail Post')
                ->assertSee('REVIEW')
                ->assertSee('Date')
                ->assertSee('Score')
                ->assertValue('i', $post->rating)
                ->assertVisible('.post-image[src="' . $post->image_url . '"]');
            $this->assertTrue($browser->text('.post-username') == $post->users->name);
            $this->assertTrue($browser->text('.post-date') == date('d-m-Y', strtotime($post->created_at)));
            $this->assertTrue($browser->text('.post-content') == $post->content);
        });
    }

    /**
     * Detail post is status
     *
     * @return void
     */
    public function testClickLinkTransfertToDetaiIsStatuslHaveComment()
    {
        $this->makeDataStatus(2);
        $post = Post::first();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts/' . $post->id)
                ->assertPathIs('/admin/posts/' . $post->id)
                ->assertSee('Detail Post')
                ->assertSee('Date')
                ->assertSee('STATUS')
                ->assertDontSee('Score')
                ->assertVisible('.post-image[src="' . $post->image_url . '"]');
            $this->assertTrue($browser->text('.post-username') == $post->users->name);
            $this->assertTrue($browser->text('.post-date') == date('d-m-Y', strtotime($post->created_at)));
            $this->assertTrue($browser->text('.post-content') == $post->content);
        });
    }

    /**
     * Detail post is status
     *
     * @return void
     */
    public function testClickLinkTransfertToDetaiIsFindBooklHaveComment()
    {
        $this->makeDataFindBook(2);
        $post = Post::first();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->user)
                ->resize(1200, 1600)
                ->visit('/admin/posts/' . $post->id)
                ->assertPathIs('/admin/posts/' . $post->id)
                ->assertSee('Detail Post')
                ->assertSee('Date')
                ->assertSee('FIND BOOK')
                ->assertDontSee('Score')
                ->assertVisible('.post-image[src="' . $post->image_url . '"]');
            $this->assertTrue($browser->text('.post-username') == $post->users->name);
            $this->assertTrue($browser->text('.post-date') == date('d-m-Y', strtotime($post->created_at)));
            $this->assertTrue($browser->text('.post-content') == $post->content);
        });
    }

    /**
     * Make data general
     *
     * @return void
     */
    public function getData()
    {
        $category = factory(Category::class)->create();
        $donator = factory(Donator::class)->create([
            'user_id' => $this->user->id,
        ]);

        $book = factory(Book::class)->create([
            'category_id' => $category->id,
            'donator_id' => $donator->id,
        ]);
        return ['category' => $category, 'donator' => $donator, 'book' => $book];
    }

    /**
     * Make data has comment
     *
     * @return void
     */
    public function makeData($row)
    {
        $faker = Faker::create();
        for ($i = 0; $i <= $row; $i++) {
            factory(Post::class)->create([
                'user_id' => $this->user->id,
                'book_id' => $this->data['book']->id,
            ]);
        }

        $postIds = DB::table('posts')->pluck('id')->toArray();
        for ($i = 0; $i <= $row; $i++) {
            factory(Comment::class)->create([
                'post_id' => $faker->randomElement($postIds),
                'user_id' => $this->user->id,
            ]);
        }

        $comments = DB::table('comments')->get();
        foreach ($comments as $comment) {
            if (isset($comment->post_id)) {
                factory(Comment::class)->create([
                    'post_id' => $comment->post_id,
                    'user_id' =>  $this->user->id,
                    'parent_id' => $comment->id
                ]);
            }
        }
    }

    /**
     * Make data has not comment
     *
     * @return void
     */
    public function makeDataNotComment($row)
    {
        $faker = Faker::create();

        for ($i = 0; $i <= $row; $i++) {
            factory(Post::class)->create([
                'user_id' => $this->user->id,
                'book_id' => $this->data['book']->id,
            ]);
        }
    }

    /**
     * Make data post is status
     *
     * @return void
     */
    public function makeDataReview($row)
    {
        $faker = Faker::create();

        for ($i = 0; $i <= $row; $i++) {
            factory(Post::class)->create([
                'user_id' => $this->user->id,
                'book_id' => $this->data['book']->id,
                'type' => Post::REVIEW_TYPE,
            ]);
        }
        $postIds = DB::table('posts')->pluck('id')->toArray();
        for ($i = 0; $i <= $row; $i++) {
            factory(Comment::class)->create([
                'post_id' => $faker->randomElement($postIds),
                'user_id' => $this->user->id,
            ]);
        }
        $posts = DB::table('posts')->get();
        foreach ($posts as $comment) {
            factory(Rating::class)->create([
                'book_id' => $comment->book_id,
                'user_id' => $this->user->id,
                'rating' => rand(1, 5),
            ]);
        }

        $comments = DB::table('comments')->get();
        foreach ($comments as $comment) {
            if (isset($comment->post_id)) {
                factory(Comment::class)->create([
                    'post_id' => $comment->post_id,
                    'user_id' => $this->user->id,
                    'parent_id' => $comment->id
                ]);
            }
        }
    }

    /**
     * Make data to test
     *
     * @return void
     */
    public function makeDataStatus($row)
    {
        $faker = Faker::create();

        for ($i = 0; $i <= $row; $i++) {
            factory(Post::class)->create([
                'user_id' => $this->user->id,
                'book_id' => $this->data['book']->id,
                'type'  => Post::STATUS_TYPE,
            ]);
        }
        $postIds = DB::table('posts')->pluck('id')->toArray();
        for ($i = 0; $i <= $row; $i++) {
            factory(Comment::class)->create([
                'post_id' => $faker->randomElement($postIds),
                'user_id' => $this->user->id,
            ]);
        }

        $comments = DB::table('comments')->get();
        foreach ($comments as $comment) {
            if (isset($comment->post_id)) {
                factory(Comment::class)->create([
                    'post_id' => $comment->post_id,
                    'user_id' => $this->user->id,
                    'parent_id' => $comment->id
                ]);
            }
        }
    }

    /**
     * Make data to status
     *
     * @return void
     */
    public function makeDataFindBook($row)
    {
        $faker = Faker::create();

        for ($i = 0; $i <= $row; $i++) {
            factory(Post::class)->create([
                'user_id' => $this->user->id,
                'book_id' => $this->data['book']->id,
                'type' => Post::FIND_TYPE,
            ]);
        }
        $postIds = DB::table('posts')->pluck('id')->toArray();
        for ($i = 0; $i <= $row; $i++) {
            factory(Comment::class)->create([
                'post_id' => $faker->randomElement($postIds),
                'user_id' => $this->user->id,
            ]);
        }

        $comments = DB::table('comments')->get();
        foreach ($comments as $comment) {
            if (isset($comment->post_id)) {
                factory(Comment::class)->create([
                    'post_id' => $comment->post_id,
                    'user_id' => $this->user->id,
                    'parent_id' => $comment->id
                ]);
            }
        }
    }

    /**
     * Make user to login
     *
     * @return void
     */
    public function makeUserLogin()
    {
        return factory(User::class)->create([
            'role' => User::ROOT_ADMIN
        ]);
    }
}
