<?php

namespace App\Http\Controllers;

use App\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseController extends Controller
{
    /**
     * @OA\Get(
     ** path="/api/get_exercises",
     *   tags={"GetExercises"},
     *   summary="GetExercises",
     *   operationId="get_exercises",
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
    public function get_exercises(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort == 'updated_at' ? 'desc' : 'asc';

        $result = Exercise::where('title', "LIKE", "%".$search."%")
            ->orWhere('description', "LIKE", "%".$search."%")
            ->orderBy($sort, $order)
            ->offset(($page-1)*$per_page)
            ->limit($per_page)
            ->get();

        $total = Exercise::where('title', "LIKE", "%".$search."%")
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
     ** path="/api/delete_exercises",
     *   tags={"DeleteExercises"},
     *   summary="DeleteExercises",
     *   operationId="delete_exercises",
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
    public function delete_exercises(Request $request) {
        $items = json_decode($request->selected_items);

        Exercise::whereIn('id', $items)->delete();

        return response()->json([
            'data' => $items,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/add_exercises",
     *   tags={"Exercises"},
     *   summary="AddExercise",
     *   operationId="add_exercises",
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
     *           format="video/*"
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
    public function add_exercises(Request $request) {
        $title = $request->get('title', '');
        $description = $request->get('description', '');
        $series = $request->get('series', 0);
        $repetitions = $request->get('repetitions', 0);
        $video_length = $request->get('video_length', 0);
        $standalone = $request->get('standalone', 0);
        $format_id = $request->get('format_id', 0);
        $published = $request->get('published', 0);

        $filepath = '';
        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
        }

        $thumb_path = '';
        if ($request->file('thumbnail') && $request->file('thumbnail')->isValid()){
            $fileName = 'thumb_'.time().'.'.$request->file('thumbnail')->extension();
            $request->file('thumbnail')->move(public_path('assets/uploads'), $fileName);
            $thumb_path = 'assets/uploads/'.$fileName;
        }

        Exercise::create([
            'title' => $title,
            'description' => $description,
            'file' => $filepath,
            'thumbnail' => $thumb_path,
            'series' => $series,
            'repetitions' => $repetitions,
            'video_length' => $video_length,
            'views' => 0,
            'standalone' => $standalone ? 1 : 0,
            'format_id' => $format_id,
            'published' => $published
        ]);

        return response()->json([
            'title' => $title,
            'description' => $description,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/update_exercises",
     *   tags={"UpdateExercises"},
     *   summary="UpdateExercises",
     *   operationId="update_exercises",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="series",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="repetitions",
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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_exercises(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title');
        $series = $request->get('series');
        $repetitions = $request->get('repetitions');

        $exercise = Exercise::findOrFail($id);
        $exercise->title = $title;
        $exercise->series = $series;
        $exercise->repetitions = $repetitions;

        $exercise->touch();
        $exercise->save();

        return response()->json([
            'data' => $id,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/update_exercises_1",
     *   tags={"Exercises"},
     *   summary="UpdateExercises",
     *   operationId="update_exercises_1",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
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
     *      name="series",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="repetitions",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="standalone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="format_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="file",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="binary",
     *           format="video/*"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="published",
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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_exercises_1(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title');
        $description = $request->get('description');
        $series = $request->get('series');
        $repetitions = $request->get('repetitions');
        $video_length = $request->get('video_length', 0);
        $standalone = $request->get('standalone');
        $format_id = $request->get('format_id');
        $published = $request->get('published');

        $filepath = '';
        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
            DB::table('exercises')->where('id', $id)->update([
                'file' => $filepath
            ]);
        }
        $thumb_path = '';
        if ($request->file('thumbnail') && $request->file('thumbnail')->isValid()){
            $fileName = 'thumb_'.time().'.'.$request->file('thumbnail')->extension();
            $request->file('thumbnail')->move(public_path('assets/uploads'), $fileName);
            $thumb_path = 'assets/uploads/'.$fileName;
            DB::table('exercises')->where('id', $id)->update([
                'thumbnail' => $thumb_path
            ]);
        }

        $exercise = Exercise::findOrFail($id);

        $exercise -> title = $title;
        $exercise -> description = $description;
        $exercise -> series = $series;
        $exercise -> repetitions = $repetitions;
        $exercise -> video_length = $video_length;
        $exercise -> views = 0;
        $exercise -> standalone = $standalone;
        $exercise -> format_id = $format_id;
        $exercise -> published = $published;

        $exercise->touch();
        $exercise->save();

        return response()->json([
            'data' => $id,
            'standalone' => $standalone
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_exercise_content",
     *   tags={"Exercises"},
     *   summary="GetExerciseContent",
     *   operationId="get_exercise_content",
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
    public function get_exercise_content(Request $request) {
        $id = $request->id;

        $content = Exercise::find($id);

        return response()->json([
            'id' => $id,
            'data' => $content
        ]);
    }

    public function increase_views(Request $request) {
        $id = $request->id;
        $exercise = Exercise::findOrFail($id);
        $exercise->increment('view');

        return response()->json([
            'views' => $exercise->views
        ]);
    }
    /**
     * Get the specified resources by filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function query(Request $request) {
        return datatables()->eloquent(Exercise::query())->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
        $exercise = Exercise::findOrFail($id);
        return response()->json($exercise);
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
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();
        return response()->json("Deleted");
    }
}
