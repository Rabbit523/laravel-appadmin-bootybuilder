<?php

namespace App\Http\Controllers;

use App\Exercise;
use App\ExerciseWeekday;
use App\Weekday;
use App\Workout;
use App\WorkoutCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    /**
     * @OA\Get(
     ** path="/api/get_workouts",
     *   tags={"Workouts"},
     *   summary="GetWorkouts",
     *   operationId="get_workouts",
     *
     *   @OA\Parameter(
     *      name="per_page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="sort",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_workouts(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort == 'updated_at' ? 'desc' : 'asc';
        $hasTimer = $request->get('has_timer', null);
        $category = $request->get('category', null);

        $result = Workout::where('published', 1)
            ->whereRaw($hasTimer === null ? '1' : "has_timer=$hasTimer")
            ->whereRaw($category === null ? '1' : "category_id=$category")
            ->where(function ($query) use ($search) {
            $query->where('title', "LIKE", "%" . $search . "%")
                ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->orderBy($sort, $order)
            ->offset(($page-1)*$per_page)
            ->limit($per_page)
            ->with('weekdays')
            ->get();

        $total = Workout::where('published', 1)
            ->whereRaw($hasTimer === null ? '1' : "has_timer=$hasTimer")
            ->whereRaw($category === null ? '1' : "category_id=$category")
            ->where(function ($query) use ($search) {
                $query->where('title', "LIKE", "%" . $search . "%")
                    ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->count();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_workouts_1",
     *   tags={"Workouts"},
     *   summary="GetWorkouts",
     *   operationId="get_workouts",
     *
     *   @OA\Parameter(
     *      name="per_page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="sort",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_workouts_1(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort == 'updated_at' ? 'desc' : 'asc';

        $result = Workout::where('title', "LIKE", "%".$search."%")
            ->orWhere('description', "LIKE", "%".$search."%")
            ->orderBy($sort, $order)
            ->offset(($page-1)*$per_page)
            ->limit($per_page)
            ->with('weekdays')
            ->get();

        $total = Workout::where('title', "LIKE", "%".$search."%")
            ->orWhere('description', "LIKE", "%".$search."%")
            ->count();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/delete_workouts",
     *   tags={"Workouts"},
     *   summary="DeleteWorkouts",
     *   operationId="delete_workouts",
     *
     *   @OA\Parameter(
     *      name="selected_items",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete_workouts(Request $request) {
        $items = json_decode($request->selected_items);
        Workout::whereIn('id', $items)->delete();
        return response()->json([
            'data' => $items,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/delete_weekday",
     *   tags={"Workouts"},
     *   summary="DeleteWeekday",
     *   operationId="delete_weekday",
     *
     *   @OA\Parameter(
     *      name="weekday_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Delete categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete_weekday(Request $request) {
        $weekday_id = $request->weekday_id;

        DB::table("weekdays")->where("id", $weekday_id)->delete();
        DB::table('exercise_weekday')->where('weekday_id', $weekday_id)->delete();

        // return response()->json()->header('Content-Type', 'text/json');
        return response()->json([
            'data' => $weekday_id,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/add_workouts",
     *   tags={"Workouts"},
     *   summary="AddWorkouts",
     *   operationId="add_workouts",
     *
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="file",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file",
     *           format="image/*"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Add new category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add_workouts(Request $request) {
        $title = $request->get('title', '');
        $description = $request->get('description', "");
        $hasTimer = intval($request->get('has_timer', 0));
        $subscribed = intval($request->get('subscribed', 0));
        $productId = $request->get('productID', "");
        $weekdays = json_decode($request->get('weekdays'));
        $published = $request->get('published', 0);
        $amount_weeks_program = $request->get('amount_weeks_program', 0);

        $category = $request->get('category', null);

        $level1_work = $request->get('level1_work', 30);
        $level1_rest = $request->get('level1_rest', 30);
        $level1_rounds = $request->get('level1_rounds', 3);
        $level2_work = $request->get('level2_work', 30);
        $level2_rest = $request->get('level2_rest', 30);
        $level2_rounds = $request->get('level2_rounds', 3);
        $level3_work = $request->get('level3_work', 30);
        $level3_rest = $request->get('level3_rest', 30);
        $level3_rounds = $request->get('level3_rounds', 3);

        $filepath = "";
        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
        }

        $workout = Workout::create([
            'title' => $title,
            'description' => $description,
            'file' => $filepath,
            'subscribed' => $subscribed,
            'has_timer' => $hasTimer,
            'productId' => $productId,
            'published' => $published,
            'amount_weeks_program' => $amount_weeks_program,
            'level1_work' => $level1_work,
            'level1_rest' => $level1_rest,
            'level1_rounds' => $level1_rounds,
            'level2_work' => $level2_work,
            'level2_rest' => $level2_rest,
            'level2_rounds' => $level2_rounds,
            'level3_work' => $level3_work,
            'level3_rest' => $level3_rest,
            'level3_rounds' => $level3_rounds,
            'category_id' => $category
        ]);

        foreach($weekdays as $weekday) {
            $data = Weekday::create([
                'workout_id' => $workout->id,
                'name' => $weekday->title
            ]);

            foreach($weekday->exercises as $exercise) {
                ExerciseWeekday::create([
                    'exercise_id' => $exercise->id,
                    'weekday_id' => $data->id
                ]);
            }
        }

        return response()->json([
            'title' => $title,
            'description' => $description,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/update_workouts",
     *   tags={"Workouts"},
     *   summary="UpdateWorkouts",
     *   operationId="update_workouts",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Update category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_workouts(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title', '');
        $description = $request->get('description', '');
        $subscribed = intval($request->get('subscribed', 0));
        $hasTimer = intval($request->get('has_timer', 0));
        $productId = $request->get('productID', "");
        $weekdays = json_decode($request->get('weekdays'));
        $published = $request->get('published', 0);
        $amount_weeks_program = $request->get('amount_weeks_program', 0);

        $category = $request->get('category', null);

        $level1_work = $request->get('level1_work', 30);
        $level1_rest = $request->get('level1_rest', 30);
        $level1_rounds = $request->get('level1_rounds', 3);
        $level2_work = $request->get('level2_work', 30);
        $level2_rest = $request->get('level2_rest', 30);
        $level2_rounds = $request->get('level2_rounds', 3);
        $level3_work = $request->get('level3_work', 30);
        $level3_rest = $request->get('level3_rest', 30);
        $level3_rounds = $request->get('level3_rounds', 3);

        $workout = Workout::findOrFail($id);

        $workout->title = $title;
        $workout->description = $description;
        $workout->subscribed = $subscribed;
        $workout->has_timer = $hasTimer;
        $workout->productId = $productId;
        $workout->published = $published;
        $workout->amount_weeks_program = $amount_weeks_program;
        $workout->level1_work = $level1_work;
        $workout->level1_rest = $level1_rest;
        $workout->level1_rounds = $level1_rounds;
        $workout->level2_work = $level2_work;
        $workout->level2_rest = $level2_rest;
        $workout->level2_rounds = $level2_rounds;
        $workout->level3_work = $level3_work;
        $workout->level3_rest = $level3_rest;
        $workout->level3_rounds = $level3_rounds;
        $workout->category_id = $category;

        $filepath = "";
        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
            $workout->file = $filepath;
        }

        $workout->touch();
        $workout->save();


        foreach($weekdays as $weekday) {
            if($weekday->id >= 0) {
                $data = Weekday::find($weekday->id);
                if (isset($data)) {
                    $data->name = $weekday->title;
                    $data->workout_id = $id;
                    $data->save();
                }

                ExerciseWeekday::where('weekday_id', $weekday->id)->delete();

                foreach($weekday->exercises as $exercise) {
                    ExerciseWeekday::create([
                        'exercise_id' => $exercise->id,
                        'weekday_id' => $weekday->id
                    ]);
                }

            } else {
                $data = Weekday::create([
                    'workout_id' => $id,
                    'name' => $weekday->title
                ]);
                foreach($weekday->exercises as $exercise) {
                    ExerciseWeekday::create([
                        'exercise_id' => $exercise->id,
                        'weekday_id' => $data->id
                    ]);
                }
            }
        }

        return response()->json([
            'title' => $title,
            'description' => $description,
            'file' => $filepath,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_workout_content",
     *   tags={"Workouts"},
     *   summary="GetWorkoutContent",
     *   operationId="get_workout_content",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Delete categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_workout_content(Request $request) {
        $id = $request->id;
        $workout = Workout::findOrFail($id);

        return response()->json([
            'id' => $id,
            'data' => $workout,
            'weekdays' => $workout->weekdays
        ]);
    }

    public function get_workout_categories_for_app(Request  $request) {
        $has_timer = $request->get('has_timer', 0);
        $categories = WorkoutCategory::whereHas('workouts', function ($query) use ($has_timer) {
            $query->where('has_timer', $has_timer);
        })->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function get_workout_categories(Request $request) {
        return response()->json([
            'categories' => WorkoutCategory::all()
        ]);
    }

    public function delete_workout_category(Request $request) {
        WorkoutCategory::findOrFail($request->get('id'))->delete();
        return response()->json([
            "success" => true
        ]);
    }

    public function create_workout_category(Request $request) {
        $category = WorkoutCategory::where('title', $request->title)->first();
        $created = false;
        if (! isset($category)) {
            $category = WorkoutCategory::create([
                "title" => $request->title,
                "description" => "category"
            ]);
            $created = true;
        }

        return response()->json([
            'category' => $category,
            "created" => $created
        ]);
    }
    /**
     * @OA\Get(
     ** path="/api/get_exercise_from_day",
     *   tags={"Workouts"},
     *   summary="GetExerciseFromDay",
     *   operationId="get_exercise_from_day",
     *
     *   @OA\Parameter(
     *      name="weekday_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="per_page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validation Exception"
     *   )
     *)
     **/
    /**
     * Get all categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_exercise_from_day(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $weekday_id = $request->weekday_id;

        $weekday = Weekday::findOrFail($weekday_id);

        $result = $weekday->exercises()
            ->offset(($page-1)*$per_page)
            ->limit($per_page)->get();

        $total = $weekday->exercises()->count();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page,
        ]);
    }

    /**
     * Get the specified resources by filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function query(Request $request) {
        return datatables()->eloquent(Workout::query())->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponseResponse
     */
    public function store(Request $request)
    {
        return response()->json("Created");
    }

    /**
     * Get the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $workout = Workout::findOrFail($id);
        return response()->json($workout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json("Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Workout::findOrFail($id);
        $user->delete();
        return response()->json("Deleted");
    }
}
