<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Gateway\AccessGateway;

class BaseController extends Controller
{
    protected ?AccessGateway $accessGateway;

    public function __construct()
    {
        $this->middleware(function(Request $request, $next){
            if ( $request->accessGateway ){
                $this->accessGateway = $request->accessGateway;
            }

            return $next($request);
        });
    }
}
