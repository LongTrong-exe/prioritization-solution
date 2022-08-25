<?php

namespace App\Http\Middleware;

use App\Repositories\ConfigurationRepositoryInterface;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckCustomerWebId
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $customerWebId = $request->get('customerWebId');

        try {
            /**
             * @var ConfigurationRepositoryInterface $configuration
             */
            $configuration = app(ConfigurationRepositoryInterface::class);
            $configuration->getConfigurationByCustomerWebId($customerWebId);
        } catch (Exception $exception) {
            return view('404');
        }

        return $next($request);
    }
}
