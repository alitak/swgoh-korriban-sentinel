<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SentinelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discord = new \Discord\Discord([
            'token' => env('DISCORD_BOT_TOKEN'),
        ]);

        $discord->on('ready', function ($discord) {
            echo "Bot is ready.", PHP_EOL;

            // Listen for events here
            $discord->on('message', function ($message) {
                if (';ks ' == substr($message->content, 0, 4)) {
                    $command = str_replace(';ks ', '', $message->content);
                    $this->run($command);
                    echo 'command: ' . $command . PHP_EOL;
                }
                echo 'end' . PHP_EOL;
                sleep(1);
            });
        });

        $discord->run();
    }

}
