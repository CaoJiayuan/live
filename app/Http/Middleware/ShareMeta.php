<?php

namespace App\Http\Middleware;

use App\Entity\Meta;
use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;

class ShareMeta
{
  protected $view;

  public function __construct(ViewFactory $view)
  {
    $this->view = $view;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $meta = Meta::getItem([
      'title','keywords'
    ]);

    $this->view->share('meta', $meta);

    return $next($request);
  }
}
