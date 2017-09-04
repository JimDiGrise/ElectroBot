<?php 
    class Bot {
        private $botUrl;
        private $botToken;
        private $httpClient;
        private $offset;
        public $lastChatId;
       
        public function __construct($botUrl, $botToken) {
            $this->httpClient = new Client([
                'base_uri' => $botUrl . $botToken,
                
		    ]);
        }
        public function getLastMessage() {
            
            $response = $this->httpClient->request('POST', 'getUpdates');
            $responseBody = json_decode($response->getBody());
            
           $length = count($responseBody->result);

           if(empty($responseBody->result[$length - 1])) {
               return FALSE;
           }

            $this->offset = (int)$responseBody->result[$length - 1]->update_id;
            $this->lastChatId = $responseBody->result[$length - 1]->message->chat->id;
           echo $this->lastChatId;
            
            return $responseBody->result[$length - 1];
            
        }
        public function confirmMessage($offset) {
            $response = $this->httpClient->request('POST', 'getUpdates', [
                'json' => [
                    'offset' => $this->offset + 1
                ]
            ]);    
        }
        public function getChatId() {
            return $this->lastChatId;
        }
        public function getLastOffset() {
            return $this->offset;
        }
       
    }
?>