<?php
namespace Tests\Browser\Pages\Backend\Categories;

use App\Model\Book;
use App\Model\Category;
use App\Model\User;
use App\Model\Donator;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class AdminListCategoriesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * make a User with role 1.
     *
     * @return void
     */
    public function userLogin()
    {
        return factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);
    }

    /**
     * A Dusk test route to page list categories.
     *
     * @return void
     */
    public function testRouteListCategories()
    {
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin')
                    ->clickLink('CATEGORIES')
                    ->assertPathIs('/admin/categories')
                    ->assertSee('List Categories');
        });
    }

    /**
     * Test layout of List Categories.
     *
     * @return void
     */
    public function testLayoutListCategories()
    {
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/categories')
                ->assertSee('List Categories')
                ->assertSeeLink('Admin')
                ->assertSee('ID')
                ->assertSee('Name')
                ->assertSee('Number of Books');
        });
    }

    /**
     * Create virtual data to test
     *
     * @return void
     */
    public function makeDataOfListCategories($rows)
    {
        factory(Category::class, $rows)->create();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        factory(Donator::class)->create();
        for ($i = 0; $i <= $rows; $i++) {
            factory(Book::class)->create([
                'category_id' => $faker->randomElement($categoryIds),
                'donator_id' => 1
            ]);
        }
    }

    /**
     * Check the list of categories without data
     *
     * @return void
     */
    public function testShowListCategoriesWithoutData()
    {
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/categories')
                    ->resize(900, 1600)
                    ->assertSee('List Categories');
                $elements = $browser->elements('#table-categories tbody tr');
                $this->assertCount(0, $elements);
        });
    }

    /**
     * Check list categories with showing only 8 rows
     *
     * @return void
     */
    public function testListCategoriesWithoutPagination()
    {
        $this->makeDataOfListCategories(8);
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/categories')
                    ->resize(900, 1600)
                    ->assertSee('List Categories');
                $elements = $browser->elements('#table-categories tbody tr');
                $this->assertCount(8, $elements);
        });
    }

    /**
     * A Dusk test Pagination
     *
     * @return void
     */
    public function testListCategoriesWithPagination()
    {
        $this->makeDataOfListCategories(25);
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/categories')
                    ->resize(900, 1600)
                    ->assertSee('List Categories');
                $elements = $browser->elements('.pagination li');
                $numberPage = count($elements) - 2;
                $this->assertTrue($numberPage == ceil(25 / (config('define.page_length'))));
        });
    }

    /**
     * Check list categories with showing than 10 rows
     *
     * @return void
     */
    public function testListCategoriesHavePagination()
    {
        $this->makeDataOfListCategories(15);
        $user = $this->userLogin();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/categories')
                    ->resize(900, 1600)
                    ->assertSee('List Categories')
                    ->click('.pagination li:nth-child(3) a');
                $elements = $browser->elements('#table-categories tbody tr');
                $this->assertCount(5, $elements);
                $browser->assertQueryStringHas('page', 2);
                $this->assertNotNull($browser->element('.pagination'));
        });
    }
}
