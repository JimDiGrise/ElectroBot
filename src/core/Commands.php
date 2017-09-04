<?php
    class Commands {
        private $bot;
        private $images;
        private $lastChatId = 0;
        private $imagesDates;
        private $imagesList;
        private $menukeyboard = array(
                'keyboard' => [
                    ["Гифку", "Фотографию"]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            );

        public function __construct($bot) {
            $this->bot = $bot;
            $this->images = new Images();
           
        }
        public function handleCommand($command) {
            if($command == "/start") {
                $this->handleStart();       
            } else if($command == "/gif" || $command == "Гифку") {
                $this->handleGif();    
            } else if($command == "/photo" || $command == "Фотографию") {
                $this->handlePhoto();    
            } else if(preg_match("/(\d+).(\d+)\s{1}(\d+):(\d+)/", $command)) {
                $this->handleDate($command);   
            } else {
                $this->handleWrong();
            }
        }
    }
?>