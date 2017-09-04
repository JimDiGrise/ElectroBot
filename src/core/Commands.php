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
        
        
    }
?>