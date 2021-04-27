<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function get()
    {
        return response()->json(
            Settings::first()
        );
    }

    public function update(Request $request)
    {
        $data = $request->get('settings');
        $data = json_decode($data);

        $settings = Settings::first();
        $settings->timer = $data->timer;
        $settings->no_timer = $data->no_timer;

        $settings->save();
    }
}
