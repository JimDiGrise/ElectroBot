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
        public function handleWrong() {
            $this->bot->sendMessage($this->lastChatId, "Команда не найдена", $this->menukeyboard);    
        }
        public function handleStart() {
            $this->bot->sendMessage($this->lastChatId, "Electro Bot - Это бот который позволяет получать фотографии нашей планеты со спутника Электро-Л. \nСайт: electro.ntsomz.ru/", $this->menukeyboard);
            $this->bot->sendMessage($this->lastChatId, "Вы можете сгенерировать gif анимацию или получить фотографию по времени, за текущие сутки. Электро-Л производит съемку и выгрузку фотографий каждые пол часа.", $this->menukeyboard);
            $this->bot->sendMessage($this->lastChatId, "Список доступных команд: \n\n /start Включение бота \n /gif Сгенерировать gif анимацию \n /photo Получить фотографию по времени \n", $this->menukeyboard);
        }
    }
?>