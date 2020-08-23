<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Browser;

use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class HomePageTest extends DuskTestCase
{

    public function testHomePageOpens()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage());
        });
    }

}
