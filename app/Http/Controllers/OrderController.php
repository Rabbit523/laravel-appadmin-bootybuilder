<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Petstore30\Order;

class OrderController extends Controller
{
    /**
     * Get the specified resources by filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function query(Request $request) {
        return datatables()->eloquent(Order::query())->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Order::findOrFail($id);
        $user->delete();
        return response()->json("Deleted");
    }
}
