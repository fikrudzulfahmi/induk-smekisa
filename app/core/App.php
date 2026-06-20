<?php

class App
{
    protected $controller = 'Home'; // Controller default
    protected $method = 'index';    // Method default
    protected $params = [];         // Parameter default

    public function __construct()
    {
        $url = $this->parseURL();

        // --- Handle Controller ---
        // Cek apakah ada file controller yang sesuai dengan nama di URL
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // --- Handle Method ---
        // Cek apakah ada method yang dikirimkan melalui URL
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // --- Handle Params ---
        // Sisa dari URL akan menjadi parameter
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Method untuk mem-parsing URL
     * @return array
     */
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');         // Hapus '/' di akhir URL
            $url = filter_var($url, FILTER_SANITIZE_URL); // Bersihkan URL dari karakter aneh
            $url = explode('/', $url);               // Pecah URL berdasarkan '/'
            return $url;
        }
        return [];
    }
}
