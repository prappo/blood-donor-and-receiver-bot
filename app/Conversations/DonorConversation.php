<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

class DonorConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $name;
    protected $mobile;
    protected $location = [];

    public function askName()
    {
        $this->ask('আপনার নাম লিখুন ', function (Answer $answer) {
            $this->name = $answer->getText();
            $this->askMobile();

        });


    }

    public function askMobile()
    {

        $this->ask('আপনার মোবাইল নাম্বারটি লিখুন', function (Answer $answer) {
            $this->mobile = $answer->getText();
            $this->askLocation();
        });
    }

    public function askLocation()
    {
        $this->ask('আপনার ঠিকানা লিখুন', function (Answer $answer) {
            $this->location = $answer->getText();
            $this->askBloodGroup();


        });
    }

    public function askBloodGroup()
    {
        $this->say(Question::create('রক্তের গ্রুপ নির্বাচন করুন')->addButtons([
            Button::create('A-positive')->value('A-positive'),
            Button::create('A-negative')->value('A-negative'),
            Button::create('B-positive')->value('B-positive'),
            Button::create('B-negative')->value('B-negative'),
            Button::create('AB-positive')->value('AB-positive'),
            Button::create('AB-negative')->value('AB-negative'),
            Button::create('O-positive')->value('O-positive'),
            Button::create('O-negative')->value('O-negative'),
        ]));


    }


    public function thankYouMessage()
    {
        $this->say('ধন্যবাদ ' . $this->name . ' ! আপনার তথ্যগুলো আমরা সংরক্ষণ করে রেখেছি  । আপনি চাইলে ভবিষ্যতে আপনার তথ্য মুছে ফেলতে পারবেন । যদি কারো রক্ত প্রয়োজন হয় আপনি একটি ম্যাসেজ পাবেন এই পেজ থেকে । আশা করছি আপনি সবসময় সাহায্যের জন্য এগিয়ে আসবেন ');
    }

    public function run()
    {
        $this->askName();
    }
}
