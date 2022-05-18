#!/usr/bin/php
<?php

require '../vendor/autoload.php';

// var_dump($argc);
// var_dump($argv[1]);

$arr = [];
$command_info = [];

$redis = new Predis\Client();
$redisResponse = $redis->ping();

if ('PONG' == $redisResponse) {
    $arr = $argv;

    $command_info['file'] = $arr[0];
    array_splice($arr, 0, 1);

    $command_info['store'] = $arr[0];
    array_splice($arr, 0, 1);

    $command_info['action'] = $arr[0];
    array_splice($arr, 0, 1);

    $command_info['key'] = $arr[0];
    array_splice($arr, 0, 1);

    $command_info['value'] = implode(' ', $arr);

    try {
        if ('add' === $command_info['action'] || 'delete' === $command_info['action']) {
            switch ($command_info['action']) {
                case 'add':
                    $redis->set($command_info['key'], $command_info['value'], 'EX', 3600);
                    echo $redis->get($command_info['key']) . ' is successfully added';
                    break;
                case 'delete':
                    $redis->del($command_info['key']);
                    echo $command_info['key'] . ' is successfully deleted';
                    break;
            }
        } else {
            throw new Exception('Invalid command action');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        die;
    }
}
