<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Ctrl_produk extends REST_Controller {

   
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function f_GetProduk(){
        $api_key = $this->get('api_key');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        $apikey = $this->f_users_get($api_key);
        
        if($apikey != false){
            $produk = $this->f_GetProdukAll($apikey['id'],$limit,$offset);
            if($produk != false){
                // Set the response and exit
                $this->response([
                                    'status' => 'success',
                                    'data' => $produk
                                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'Product not found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }

        }else{
            // Set the response and exit
            $this->response([
                                'status' => false,
                                'message' => 'No users were found'
                            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }



}