<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Cookie;
use App\Models\Settings;

class AffiliateReff
{
    public function handle($request, Closure $next)
    {
		if(!empty($request->reff))

		{

			$affilate_user = User::where('username','=',$request->reff)->first();

			if(!empty($affilate_user))
			{
				if(Settings::getOption('is_affilate') == 1)

				{

					Cookie::queue(Cookie::forget('affilate'));

					Cookie::queue(Cookie::make('affilate', $affilate_user->username, 86400));

					

					$request->query->remove('reff');

				}

			}

		}

        

        return $next($request);

    }

}