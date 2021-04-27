<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="BootyBuilder OpenApi",
     *      description="BootyBuilder Swagger OpenApi",
     *      @OA\Contact(
     *          email="tester@kr-stardev.com"
     *      ),
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     */

    /**
     *  @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Bootybuilder Swagger OpenApi host server"
     *  )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
