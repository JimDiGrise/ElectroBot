<?php 
    require "/../../vendor/autoload.php";
    use GuzzleHttp\Client;    
    
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
        public function sendMessage($chatId, $message, $keyboard ) { 
            $response = $this->httpClient->request('POST', 'sendMessage', [
			'json' => ['chat_id' => $chatId, 
						'text' => $message, 
                        'reply_markup' => json_encode($keyboard)
                    ]
            ]);
            return $response->getStatusCode();
        }
        public function sendPhoto($chatId, $path ) { 
            $res = $this->httpClient->request('POST', 'sendPhoto', [
			'multipart' => [
                [
                    'name'     => 'photo',
                    'contents' => fopen($path, 'r'),
                ],
                [
                    'name'     => 'chat_id',
                    'contents' => $chatId,
                ],
            ]    
            ]);	
            return $res->getStatusCode();
        }
        public function sendGif($chatId, $path) {
            $res = $this->httpClient->request('POST', 'sendDocument', [
			'multipart' => [
                [
                    'name'     => 'document',
                    'contents' => fopen($path, 'r'),
                ],
                [
                    'name'     => 'chat_id',
                    'contents' => $chatId,
                ],
            ]    

            ]);	
            return $res->getStatusCode();
            
        }
        public function sendChatAction($chatId, $action) {
            $res = $this->httpClient->request('POST', 'sendChatAction', [
                'json' => [ 'chat_id' => $chatId, 
                            'action' => $action, 
                ]

            ]);	
            return $res->getStatusCode();
            
        }
       
    }
?>