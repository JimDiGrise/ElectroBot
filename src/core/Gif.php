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
        public function createGif() 
        {   
            $gc = new GifCreator();
            $duration = array_fill(0, count($this->images), 40);
            $gc->create($this->images, $duration); 
            $this->logger->info("Create gif");          
            return $this->saveGif($gc->getGif());
            
        }
        private function saveGif($binary) 
        {
            try {
                if(empty($this->gifPath)) throw new Exception("Path is empty");
                if(empty($binary)) throw new Exception("Binary is empty");
                return file_put_contents($this->gifPath, $binary) ? TRUE : FALSE;
            } catch(Exception $e) {
                $this->logger->info("Exeption: " . $e->getMessage());
            }
        }
     
    }
    
?>