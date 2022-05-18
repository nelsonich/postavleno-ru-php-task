<?php

namespace App\Controllers;

use System\Controller;
use System\Request;
use Predis;

class HomePageController extends Controller
{

    protected $redis = null;

    public function __construct()
    {
        $this->redis = new Predis\Client();
    }

    function __destruct()
    {
        //Disconnect from Redis
        $this->redis->disconnect();
    }

    public function index()
    {
        return $this->view('home_page');
    }

    public function getData()
    {
        $list = $this->redis->keys("*");


        sort($list);

        $arr = [];
        foreach ($list as $key) {
            $value = $this->redis->get($key);

            $arr[$key] = $value;
        }

        return $this->json(json_encode([
            'data' => $arr,
            'status' => true,
            'code' => 200,
        ]));
    }

    public function deleteRow(Request $request)
    {
        $key = $request->param('key');

        $this->redis->del($key);

        $list = $this->redis->keys("*");
        sort($list);

        $arr = [];
        foreach ($list as $key) {
            $value = $this->redis->get($key);

            $arr[$key] = $value;
        }

        return $this->json(json_encode([
            'data' => $arr,
            'status' => true,
            'code' => 200,
        ]));
    }
}
