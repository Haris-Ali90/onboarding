<?php

namespace App\Http\Controllers\Admin;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('login');
    }

    public function home()
    {

        /*if(!Auth::check())
        {
            dd('init');
        }*/

        // checking auth
        if (auth()->user()->login_type == "micro_hub")
        {
            //dd(redirect()->route('micro-hub.joeys.statistics'));
            return redirect()->route('micro-hub.joeys.statistics');
        }
        else
        {
            return redirect()->route('joeys.statistics');
        }

        // redirect to there dashboard

        return redirect()->route('login');
    }

    public function csvcheck(){
        return backend_view('cors-chcek');
    }


}
