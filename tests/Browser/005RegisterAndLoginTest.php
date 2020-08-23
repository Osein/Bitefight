<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterAndLoginTest extends DuskTestCase
{

    public function testRegisterViewIsSelectRaceWithoutParameter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(url('/register'))
                    ->assertSee(__('home.home_register_select_race'));
        });
    }

    public function testRegisterPageWithRaceOpensForm()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit(url('/register?race=1'))
                ->assertSee(__('home.home_register_name_label'));
        });
    }

}
