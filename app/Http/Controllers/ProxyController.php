<?php

namespace App\Http\Controllers;

use App\Events\ProxyChecked;
use App\Jobs\CheckProxy;
use App\Models\Proxy;
use App\Models\ProxyLog;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ProxyController extends Controller
{
    /**
     * Checks proxy server
     * 
     * @param   \Illuminate\Http\Request $request
     * @return  View
     */
    public function check(Request $request)
    {
        // 195.189.60.97:8080

        $proxies = explode("\n", str_replace("\r", "", $request->proxy_list));

        // Proxy::truncate();

        $proxy_log = new ProxyLog;
        $proxy_log->total = count($proxies);
        $proxy_log->save();

        $arr = [];

        foreach ($proxies as $proxy) {
            array_push($arr, new CheckProxy($proxy_log->id, $proxy));
        }

        $batch = Bus::batch($arr)
            ->then(function (Batch $batch) {
                //
            })->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected...
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
            })->dispatch();

        return redirect()->route('result', ['log_id' => $proxy_log->id]);
    }

    /**
     * Display a page to check proxy.
     *
     * @return View
     */
    public function index()
    {
        return view('check');
    }

    /**
     * Shows proxy check results
     * 
     * @return View
     */
    public function result($log_id)
    {
        return view('result')
            ->with('proxies', Proxy::all()->where('log_id', $log_id))
            ->with('log', ProxyLog::where('id', $log_id)->first());
    }

    /**
     * Shows proxy checks history
     * 
     * @return View
     */
    public function archive()
    {
        $logs = ProxyLog::paginate();

        return view('archive')->with('logs', $logs);
    }
}
