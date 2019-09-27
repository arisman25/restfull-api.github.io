<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Produk extends CI_Model {

    var $db = $this->db;
    var $img_url = 'https://inc.mizanstore.com/aassets/img/com_cart/produk/';
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

    public function f_GetProdukAll($client_id,$limit,$offset){
        $q = $db->query("SELECT a.bara AS PRODUCT_ID,  a.nama AS `NAME`, a.judul_produk AS TITLE, a.keterangan AS DESCRIPTION,
        a.jenis_produk AS `TYPE`, a.berat AS `WEIGHT`, a.panjang AS `LENGTH`, a.lebar AS WIDTH, a.tinggi AS HEIGHT,
        CONCAT('".$img_url."',a.gambar) AS IMAGE, CONCAT('".$img_url."',a.gambar_thumb) AS IMAGE_THUMB,
        a.nama_penulis AS AUTHOR, a.nama_vendor AS VENDOR, IFNULL(a.discount,0) AS DISCOUNT,
        CASE WHEN QUANTITY_CHECK = 1 THEN a.quantity-a.stock_minimum ELSE a.quantity_toko-a.stock_minimum END AS QUANTITY,
        a.url_title AS URL_TITLE FROM produk AS a INNER JOIN client_product AS b ON (a.bara = b.bara) WHERE 
        a.client_id = '".$client_id."' ORDER BY a.date_added DESC LIMIT '".$limit."','".$offset."'");
        if($q->num_rows() > 0){
            return $q->result_array();
        }else{
            return false;
        }
    }

    public function f_users_get($api_key)
    {
        $q = $db->query("SELECT a.id, a.`code`, a.`name`, a.api_key FROM client AS a WHERE a.api_key = '".$api_key."' AND a.`status` = 1 ");
        if($q->num_rows() > 0){
            return $q->result_array()[0];
        }else{
            return false;
        }
    }   

}