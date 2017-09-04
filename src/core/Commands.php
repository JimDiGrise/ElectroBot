<?php
    require "../vendor/autoload.php";

    require "core/Gif.php";
    require "core/Images.php";
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
        public function handlePhoto() {
            $this->clearDirectory("img/");
            $this->clearDirectory("gif/");
            $this->bot->sendChatAction($this->lastChatId, "upload_photo" );
            $this->images->getAllImages();
            $imagesDates = $this->images->getImagesDates();
            $firstDay = array();
            foreach($imagesDates as $image) {
                array_push($firstDay, $image->format("m.d H:i"));
            }
            $datesKeyboard = array(
                'keyboard' => array_chunk($firstDay, 4),
                'resize_keyboard' => true,
                'one_time_keyboard' => true
                
            );
            $this->bot->sendMessage($this->lastChatId, "Выберите дату и время: ", $datesKeyboard);        
        }
        public function handleDate($command) {
            
            $date = preg_split("/\s/", $command);
            $firstDay = array();
            $currentDate = new DateTime("2017-". preg_split("/\./", $date[0])[0] . "-" . preg_split("/\./", $date[0])[1]);
            $currentDate->setTime(preg_split("/:/", $date[1])[0], preg_split("/:/", $date[1])[1]);
            $this->bot->sendMessage($this->lastChatId, "Фотография сделана в " . $currentDate->format("d.m H:i") . ":" , $this->menukeyboard); 
            $imagePath = $this->images->getImage($currentDate);
            echo ( "Date " . $imagePath) ;
            $this->bot->sendPhoto($this->lastChatId, $imagePath);
      
        }
        public function handleGif() {
            $this->clearDirectory(__DIR__  . "/../../img/");
            $this->clearDirectory(__DIR__ . "/../../gif/");
            $this->bot->sendChatAction($this->lastChatId, "upload_photo" );
            $this->images->getAllImages();
            $imagesList = $this->images->getImagesList();
            print_R($imagesList);
            $gif = new Gif($imagesList);
            $gif->createGif();
            $this->bot->sendGif($this->lastChatId,  __DIR__. "/../../gif/earth.gif" ); 
            $this->bot->sendMessage($this->lastChatId, "Эта анимация была создана из фотографий отснятых за текущие сутки. ", $this->menukeyboard); 
        }
        public function clearDirectory($url) {
            foreach(glob($url . "*") as $filename) {
                unlink($filename);
            }
        }
        public function setLastChatId($chatId) {
            $this->lastChatId = $chatId;
        }
    }
?>