<?php

namespace App\Http\Controllers;

use App\Conversations\DonorConversation;
use App\Http\Middleware\DefaultMiddleware;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\QuickReplyButton;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');


        $botman->hears('hi', function ($bot) {
            $bot->reply(ButtonTemplate::create('একটি অপশন বাছাই করুন')
                ->addButton(ElementButton::create('রক্ত খুঁজছি')
                    ->type('postback')
                    ->payload('receiver')
                )
                ->addButton(ElementButton::create('রক্ত দিব')
                    ->type('postback')
                    ->payload('donor')
                )
            );


        });

        $botman->hears('donor', function ($bot) {
            $bot->startConversation(new DonorConversation());
        });

        $botman->hears('receiver', function ($bot) {

            $bot->reply(Question::create('রক্তের গ্রুপ নির্বাচন করুন')->addButtons([
                Button::create('A-positive')->value('A-positive-blood'),
                Button::create('A-negative')->value('A-negative'),
                Button::create('B-positive')->value('B-positive'),
                Button::create('B-negative')->value('B-negative'),
                Button::create('AB-positive')->value('AB-positive'),
                Button::create('AB-negative')->value('AB-negative'),
                Button::create('O-positive')->value('O-positive'),
                Button::create('O-negative')->value('O-negative'),
            ]));

        });

        $receiveMiddleware = new DefaultMiddleware();

        $botman->middleware->received($receiveMiddleware);

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }


    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
