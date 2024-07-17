<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Auth;
use App\Models\Languages;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }else if(session()->has('localelogin')){
            App::setLocale(session()->get('localelogin'));

        }
        else{
            if (Auth::user()){
                $langs = '';
                $user = Auth::user();
                if(isset($user->language_id)){
                    $langs = Languages::find($user->language_id);
                    App::setLocale(strtolower($langs->name));
                }
            }
        }
        return $next($request);
    }
}
?>
