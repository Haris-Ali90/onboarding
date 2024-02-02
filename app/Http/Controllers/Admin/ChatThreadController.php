<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


class ChatThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();

    }

    public function index(Request $request)
    {
        return view('admin.threads.index');
    }


}
