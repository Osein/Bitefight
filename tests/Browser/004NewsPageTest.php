<?php

namespace Tests\Browser;

use Database\Models\News;
use Illuminate\Support\Facades\Lang;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class NewsPageTest extends DuskTestCase
{

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            News::truncate();

            $browser->visit('/news')
                ->assertSee(Lang::get('home.news_empty'));

            $news = new News();
            $news->setAddedUserId(1);
            $news->setTitle('News from testing');
            $news->setMessage('Message of news');
            $news->setAddedTime(time());
            $news->save();

            $browser->visit('/news')
                ->assertSee('News from testing');
        });
    }

}
