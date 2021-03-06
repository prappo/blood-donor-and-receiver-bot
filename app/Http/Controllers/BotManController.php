<?php

namespace App\Http\Controllers;

use App\Conversations\DonorConversation;
use App\FbUsers;
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


        $botman->hears('GET_STARTED', function ($bot) {

            $bot->reply(ButtonTemplate::create('একটি অপশন বাছাই করুন')
                ->addButton(ElementButton::create('রক্তদাতা খুঁজুন')
                    ->type('postback')
                    ->payload('receiver')
                )
                ->addButton(ElementButton::create('রক্তদাতা নিবন্ধন ')
                    ->type('postback')
                    ->payload('donor')
                )
            );


        });

        $botman->hears('DELETE_MY_ACCOUNT', function (BotMan $bot) {

            $bot->reply(ButtonTemplate::create('আপনি কি আপনার প্রোফাইল রক্তদাতাদের তালিকা থেকে সরাতে চান ?')
                ->addButton(ElementButton::create('হ্যাঁ')
                    ->type('postback')
                    ->payload('DEACTIVATE_MY_ACCOUNT')
                )
                ->addButton(ElementButton::create('না')
                    ->type('postback')
                    ->payload('NOT_DEACTIVATE_MY_ACCOUNT')
                )

            );

        });

        $botman->hears('DEACTIVATE_MY_ACCOUNT', function (BotMan $bot) {

            $id = $bot->getUser()->getId();
            $user = FbUsers::where('fbId', $id)->first();

            if ($user->status != 'active') {
                $bot->reply('আপনার প্রোফাইল রক্তদাতাদের তালিকায় নেই ');
            }

            FbUsers::where('fbId', $id)->update([
                'status' => 'deactive'
            ]);

            $bot->reply('আপনার প্রোফাইল রক্তদাতাদের লিস্ট থেকে সরিয়ে নেয়া হয়েছে');

        });

        $botman->hears('NOT_DEACTIVATE_MY_ACCOUNT', function (BotMan $bot) {
            $bot->reply('ধন্যবাদ');
        });

        $botman->hears('MY_ACCOUNT', function (BotMan $bot) {

            $fbId = FbUsers::where('fbId', $bot->getUser()->getId())->first();
            $bot->reply('নাম : ' . $fbId->name);
            $bot->reply('মোবাইল : ' . $fbId->mobile);
            $bot->reply('লোকেশন : ' . $fbId->location);
            $bot->reply('রক্তের গ্রুপ : ' . str_replace('-', ' ', $fbId->blood_group));

            if ($fbId->status = 'active') {
                $bot->reply('আপনি সক্রিয় রক্তদাতা হিসবে নিবন্ধিত আছেন ');
            } else {
                $bot->reply('আপনার সক্রিয় রক্তদাতা হিসেবে নিবন্ধিত নেই। আপনার প্রোফাইল রক্তদাতাদের তালিকায় নেই । আপনি চাইলে নিবন্ধন করতে পারেন ');
            }

        });


        $botman->hears('donor', function ($bot) {
            $bot->startConversation(new DonorConversation());
        });

        $botman->hears('receiver', function ($bot) {

            $bot->reply(Question::create('আপনি যেই রক্তের গ্রুপের রক্তদাতা খুঁজছেন সেই রক্তের গ্রুপ নির্বাচন করুন ')->addButtons([

                Button::create('A positive')->value('SEARCH_BLOOD_A-positive'),
                Button::create('A negative')->value('SEARCH_BLOOD_A-negative'),
                Button::create('B positive')->value('SEARCH_BLOOD_B-positive'),
                Button::create('B negative')->value('SEARCH_BLOOD_B-negative'),
                Button::create('AB positive')->value('SEARCH_BLOOD_AB-positive'),
                Button::create('AB negative')->value('SEARCH_BLOOD_AB-negative'),
                Button::create('O positive')->value('SEARCH_BLOOD_O-positive'),
                Button::create('O negative')->value('SEARCH_BLOOD_O-negative'),

            ]));

        });

        $botman->hears('SET_BLOOD_GROUP_USER_.*', function (BotMan $bot) {

            $blood_group = str_replace('SET_BLOOD_GROUP_USER_', '', $bot->getMessage()->getText());

            FbUsers::where('fbId', $bot->getUser()->getId())->update([
                'blood_group' => $blood_group,
                'status' => 'active',
                'type' => 'donor'
            ]);

            $bot->reply('ধন্যবাদ ' . $bot->getUser()->getFirstName() . ' ! আপনার তথ্যগুলো আমরা সংরক্ষণ করে রেখেছি  । আপনি চাইলে ভবিষ্যতে আপনার তথ্য মুছে ফেলতে পারবেন অথবা পরিবর্তন করতে পারবেন  । যদি কারো রক্ত প্রয়োজন হয় আপনি একটি ম্যাসেজ পাবেন এই পেজ থেকে । আশা করছি আপনি সবসময় সাহায্যের জন্য এগিয়ে আসবেন ');

            $donor = FbUsers::where('fbId', $bot->getUser()->getId())->first();

            $bot->reply('আপনার তথ্য ঃ');
            $bot->reply('নাম : ' . $donor->name);
            $bot->reply('মোবাইল নাম্বার : ' . $donor->mobile);
            $bot->reply('লোকেশন : ' . $donor->location);
            $bot->reply('রক্তের গ্রুপ : ' . str_replace('-', ' ', $donor->blood_group));


        });

        $botman->hears('SEARCH_BLOOD_.*', function (BotMan $bot) {


            $blood_group = str_replace('SEARCH_BLOOD_', '', $bot->getMessage()->getText());

            if (FbUsers::where('status', 'active')->where('blood_group', $blood_group)->count() != 0) {
                $bot->reply(FbUsers::where('status', 'active')->where('blood_group', $blood_group)->count() . ' জন রক্তদাতা পাওয়া গিয়েছে । তালিকা নিম্নে দেওয়া হল ');
                foreach (FbUsers::where('status', 'active')->where('blood_group', $blood_group)->get() as $donor) {

                    $bot->reply(ButtonTemplate::create($donor->name)
                        ->addButton(ElementButton::create('বিস্তারিত')
                            ->type('postback')
                            ->payload('DONOR_DETAILS_' . $donor->fbId)
                        )
                        ->addButton(ElementButton::create('কল করুন')
                            ->type('phone_number')
                            ->payload($donor->mobile)
                        )
                    );

                }
            } else {
                $bot->reply('দুঃখিত কোন রক্তদাতা পাওয়া যায়নি ');
            }


        });

        $botman->hears('DONOR_DETAILS_.*', function (BotMan $bot) {

            $id = str_replace('DONOR_DETAILS_', '', $bot->getMessage()->getText());
            $donor = FbUsers::where('fbId', $id)->first();
            $bot->reply('নাম : ' . $donor->name);
            $bot->reply('মোবাইল নাম্বার : ' . $donor->mobile);
            $bot->reply('লোকেশন : ' . $donor->location);
            $bot->reply('রক্তেরগ্রুপ : ' . str_replace('-', ' ', $donor->blood_group));

        });

        $botman->fallback(function (Botman $bot) {

            $bot->reply('"রক্তদাতা নিবন্ধন  এবং নিন" একটি স্বয়ংক্রিয় মাধ্যম যেখানে আপনি রক্তদাতা হিসেবে নিবন্ধন করতে পারবেন এবং রক্ত প্রয়োজন হলে রক্তদাতাদের খুঁজতে পারবেন । এই পেইজ এর মেসেজিং কোন মানুষদ্বারা নিয়ন্ত্রিত নয় । ম্যাসেজ এর উত্তর স্বয়ংক্রিয় ।');

            $bot->reply(ButtonTemplate::create('একটি অপশন বাছাই করুন')
                ->addButton(ElementButton::create('রক্তদাতা খুঁজুন')
                    ->type('postback')
                    ->payload('receiver')
                )
                ->addButton(ElementButton::create('রক্তদাতা নিবন্ধন ')
                    ->type('postback')
                    ->payload('donor')
                )
            );

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
