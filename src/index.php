<?php 
    require "../vendor/autoload.php";

    
    require "core/Bot.php";
    require "core/Commands.php";
    
    $bot = new Bot("https://api.telegram.org/", "bot310341855:AAGF60Bu1mHjDjjEn31ekxwJmKw-OMTBlqg/");
    while(1) {
        sleep(3);
        $cmd = new Commands($bot);
        if(!empty($bot->getLastMessage())) {
            $lastMessage = $bot->getLastMessage();
            $cmd->setLastChatId($bot->lastChatId);
            $bot->confirmMessage($bot->lastChatId);
           $cmd->handleCommand($lastMessage->message->text);
        }
    }
?>