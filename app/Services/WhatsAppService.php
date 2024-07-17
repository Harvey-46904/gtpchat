<?php

namespace App\Services;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class WhatsAppService
{
    public function enviar_wasap($campañaId,$nombre_campaña,$nombres,$apellidos,$telefono)
    {

        $data_api=self::envio($nombres,$apellidos,$telefono);
      
       $data= DB::table('publicidads')
            ->where('id', $campañaId)
            ->update(
                [
                    'nombre_campaña' => $nombre_campaña,
                    'estado_local'=>"Iniciado",
                    'estado_servicio'=>$data_api
            ]);
        
    }
    public function envio($nombres,$apellidos,$telefonos){
        $client = new Client();

        $nombre_completo=$nombres." ".$apellidos;
        $numero_celular="57".$telefonos;
        $parameters = [
            [
                'type' => 'text',
                'text' => $nombre_completo
            ],
            
        ];
        try {
            $response = $client->post('https://graph.facebook.com/v18.0/209070518953772/messages', [
                'headers' => [
                    'Authorization' => 'Bearer EAAKmQ1rbPCMBOwJpZCPDi2B4ZBClPJn7ISJJO3XkeRoRP79ZAZBj6Qxix3jTYbRN87ZCpbdnDJHMEZCiOWyZAczbchwpFUG3TJaAy2cJMwkVOyvAyrtncSMsy1wlYZB61W5cULMPoTDolWhz1ptnmLgLRF4SFDOPpsFIXMOIJdCDNPSL3MqYvGuIQambBf2FyVrj',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'messaging_product' => 'whatsapp',
                    'to' => $numero_celular,
                    'type' => 'template',
                    'template' => [
                        'name' => 'prueba2',
                        'language' => [
                            'code' => 'es'
                        ],
                        
                        'components' => [
                            [
                                'type'=> 'header', 
                                'parameters'=> [ 
                                    [
                                        'type'=> 'image', 
                                        'image'=> [
                                            'link'=> "https://globalinternational.edu.co/wp-content/uploads/2024/04/WhatsApp-Image-2024-04-06-at-12.59.38-PM.jpeg"
                                        ]  
                                    ]
                                         
                                    
                                        ] 
                            ],
                            [
                                'type' => 'body',
                                'parameters' => $parameters
                            ]
                        ]
                    ]
                ]
            ]);

            $response = $response->getBody();
            $data = json_decode($response, true);
            $messageStatus = $data['messages'][0]['message_status'];
            return $messageStatus;

        } catch (RequestException $e) {
            return  "Error: " . $e->getMessage();
        }
  }       
}
