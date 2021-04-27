<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exercise;
use App\Workout;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function statistics() {
        $exercises = Exercise::count();
        $workouts = Workout::count();
        $categories = Category::count();
        $publishedExercises = Exercise::where('published', 1)->count();
        $publishedWorkouts = Workout::where('published', 1)->count();
        $publishedCategories = Category::where('published', 1)->count();

        return response()->json(
            [
                "exercises" => [
                    "total" =>$exercises,
                    "published" => $publishedExercises
                ],
                "workouts" => [
                    "total" => $workouts,
                    "published" => $publishedWorkouts
                ],
                "categories" => [
                    "total" => $categories,
                    "published" => $publishedCategories
                ]
            ]
        );
    }
}
