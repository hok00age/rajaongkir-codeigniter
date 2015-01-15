<?php

/**
 * RajaOngkir CodeIgniter Library
 * Digunakan untuk mengkonsumsi API RajaOngkir dengan mudah
 * 
 * @author Damar Riyadi <damar@tahutek.net>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class RajaOngkir {

    private $api_key, $base_url;

    public function __construct() {
        // Pastikan bahwa PHP mendukung cURL
        if (!$this->is_curl_exists()) {
            log_message('error', 'cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.');
        }
        $this->_ci = & get_instance();
        $this->_ci->load->config('rajaongkir');
        // Pastikan Anda sudah memasukkan API Key di application/config/rajaongkir.php
        if ($this->_ci->config->item('api_key') == "") {
            log_message("error", "Harap masukkan API KEY Anda di config.");
        } else {
            $this->api_key = $this->_ci->config->item('api_key');
            $this->base_url = $this->_ci->config->item('base_url');
        }
    }

    /**
     * Fungsi untuk memeriksa apakah PHP yang terinstal sudah mendukung cURL
     * @return boolean TRUE jika cURL terinstal
     */
    private function is_curl_exists() {
        return function_exists('curl_init');
    }

    /**
     * Fungsi yang berperan sebagai REST client
     * @param string $method HTTP method
     * @param string $url API endpoint RajaOngkir
     * @param array $params Parameter yang dikirim ke RajaOngkir
     * @return string Response body, berupa string JSON balasan dari RajaOngkir
     */
    private function curl($method, $url, $params) {
        $curl = curl_init();
        $header[] = "Content-Type: application/x-www-form-urlencoded";
        $header[] = "key: $this->api_key";
        $query = http_build_query($params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        } else if ($method == "GET") {
            curl_setopt($curl, CURLOPT_URL, $url . "?" . $query);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $request = curl_exec($curl);
        $return = ($request === FALSE) ? curl_error($curl) : $request;
        curl_close($curl);
        return $return;
    }

    /**
     * Fungsi untuk mendapatkan data propinsi di Indonesia
     * @param integer $province_id ID propinsi, jika NULL tampilkan semua propinsi
     * @return string Response dari cURL, berupa string JSON balasan dari RajaOngkir
     */
    function getProvince($province_id = NULL) {
        $params = (is_null($province_id)) ? array() : array('id' => $province_id);
        return $this->curl("GET", $this->base_url . "province", $params);
    }

    /**
     * Fungsi untuk mendapatkan data kota di Indonesia
     * @param integer $province_id ID propinsi
     * @param integer $city_id ID kota, jika ID propinsi dan kota NULL maka tampilkan semua kota
     * @return string Response dari cURL, berupa string JSON balasan dari RajaOngkir
     */
    function getCity($province_id = NULL, $city_id = NULL) {
        $params = (is_null($province_id)) ? array() : array('province' => $province_id);
        if (!is_null($city_id)) {
            $params['id'] = $city_id;
        }
        return $this->curl("GET", $this->base_url . "city", $params);
    }

    /**
     * Fungsi untuk mendapatkan data ongkos kirim
     * @param integer $origin ID kota asal
     * @param integer $destination ID kota tujuan
     * @param integer $weight Berat kiriman dalam gram
     * @param string $courier Kode kurir, jika NULL maka tampilkan semua kurir
     * @return string Response dari cURL, berupa string JSON balasan dari RajaOngkir
     */
    function getCost($origin, $destination, $weight, $courier = NULL) {
        $params = array(
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight
        );
        if (!is_null($courier)) {
            $params['courier'] = $courier;
        }
        return $this->curl("POST", $this->base_url . "cost", $params);
    }

}
