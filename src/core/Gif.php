<?php 
    

    require "../vendor/autoload.php";
    
    use PHPHtmlParser\Dom;
    use GifCreator\GifCreator;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
   
    class Gif 
    {
        private $images = array();
        private $gifPath =  __DIR__ . '/../../gif/earth.gif';
        private $logger;
        public function __construct($images) {
            $this->images = $images;
            $this->logger = new Logger('Gif');
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/main.log', Logger::INFO));
        }
       
     
    }
    
?>