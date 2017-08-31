<?php 
    
    require "../vendor/autoload.php";
    
    use PHPHtmlParser\Dom;
    use GifCreator\GifCreator;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    


    class Images 
    {
        private $sourceImage = "http://electro.ntsomz.ru/";
        private $images = array();
        private $logger;
        public function __construct() {
            $this->logger = new Logger('Images');
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../log/main.log', Logger::INFO));
        }
        
        private function parseSource() {
            $dom = new Dom;
            $dom->load($this->sourceImage);
            return $dom->find('.imgresize');
        }
        public function getAllImages() {
            $parsedHtml = $this->parseSource();
            $this->saveImages($parsedHtml);
        }
        private function saveImage($url) {
            $imagePath = __DIR__ . "/../../img/" . basename($url, ".jpg") . ".jpg";
            try {
                return file_put_contents($imagePath, file_get_contents($this->sourceImage .  $url)) ? TRUE : FALSE;
            } catch(Exception $e) {
                $this->logger->error("Exception: " . $e);
            }

        }

    }
    
?>