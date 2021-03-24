<?php

namespace App\Jobs;

use App\Events\ProxyChecked;
use App\Models\Proxy;
use App\Models\ProxyLog;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckProxy implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    protected $proxy;
    protected $proxy_log_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($proxy_log_id, string $proxy)
    {
        $this->proxy = $proxy;
        $this->proxy_log_id = $proxy_log_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = $this->getProxyInfo($this->proxy);

        $proxy_log = ProxyLog::where('id', $this->proxy_log_id)->first();

        if ($result) {
            $new_proxy = Proxy::create([
                'ip' => $result['ip'],
                'type' => $result['type'],
                'country_city' => $result['country_city'],
                'status' => $result['status'],
                'speed' => $result['speed'],
                'real_ip' => $result['real_ip'],
                'log_id' => $this->proxy_log_id,
            ]);
            $proxy_log->alive += 1;
        } else {
            $new_proxy = Proxy::create([
                'ip' => $this->proxy,
                'type' => null,
                'country_city' => null,
                'status' => 0,
                'speed' => null,
                'real_ip' => null,
                'log_id' => $this->proxy_log_id,
            ]);
        }
        $new_proxy->save();

        $proxy_log->complete += 1;
        $proxy_log->save();
    }

    private $url = 'https://google.com/';
    private $timeout = 5;

    /**
     * Get proxy location by IP
     * 
     * @param   string  $ip
     * @return  Json
     */
    private function getLocation(string $ip)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$ip?fields=status,message,country,regionName,city&lang=ru");
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    /**
     * Returns neccesary info about proxy IP with exact proxy type
     * 
     * @param   string $ip
     * @return  array
     */
    private function getProxyInfoWithProxyType(string $ip, int $proxyType)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_PROXY, $ip);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($result) {
            $ipip = substr($ip, 0, strpos($ip, ':'));
            $location = $this->getLocation($ipip);
            // dd($location);

            return [
                'ip' => $ip,
                // 'type' => $info->foo,
                'country_city' => $location->country . ', ' . $location->city,
                'status' => boolval($result),
                'speed' => $info['speed_download'],
                'real_ip' => $info['primary_ip'],
            ];
        } else {
            return false;
        }
    }

    /**
     * Returns neccesary info about proxy IP
     * 
     * @param   string $ip
     * @return  Json
     */
    private function getProxyInfo(string $ip)
    {
        $result = null;

        if ($result = $this->getProxyInfoWithProxyType($ip, CURLPROXY_SOCKS5)) {
            $result += ['type' => 'socks'];
            return $result;
        }
        if ($result = $this->getProxyInfoWithProxyType($ip, CURLPROXY_SOCKS4)) {
            $result += ['type' => 'socks'];
            return $result;
        }
        if ($result = $this->getProxyInfoWithProxyType($ip, CURLPROXY_HTTP)) {
            $result += ['type' => 'http'];
            return $result;
        }

        return false;
    }
}
