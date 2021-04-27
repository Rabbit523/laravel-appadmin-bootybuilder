<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryTag;
use App\Exercise;
use App\ExerciseTag;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     ** path="/api/get_categories",
     *   tags={"Categories"},
     *   summary="GetCategories",
     *   operationId="get_categories",
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
     * Get all categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_categories(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort == 'updated_at' ? 'desc' : 'asc';

        $result = Category::where('published', 1)
            ->where(function ($query) use ($search) {
                $query->where('title', "LIKE", "%" . $search . "%")
                    ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->orderBy($sort, $order)
            ->offset(($page-1)*$per_page)
            ->limit($per_page)
            ->with('tags')
            ->get();

        $total = Category::where('published', 1)
            ->where(function ($query) use ($search) {
                $query->where('title', "LIKE", "%" . $search . "%")
                    ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->count();


        foreach($result as $item) {
            $item->total_exercise = $item->count_exercises();
        }

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_categories_1",
     *   tags={"Categories"},
     *   summary="GetCategories",
     *   operationId="get_categories",
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
     * Get all categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_categories_1(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort == 'updated_at' ? 'desc' : 'asc';

        $result = Category::where('title', "LIKE", "%" . $search . "%")
            ->orWhere('description', "LIKE", "%" . $search . "%")
            ->orderBy($sort, $order)
            ->offset(($page-1)*$per_page)
            ->limit($per_page)
            ->with('tags')
            ->get();

        $total = Category::where('title', "LIKE", "%" . $search . "%")
            ->orWhere('description', "LIKE", "%" . $search . "%")
            ->count();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_categories_qrcode_checked",
     *   tags={"Categories"},
     *   summary="GetCategories_QRCodeChecked",
     *   operationId="get_categories_qrcode_checked",
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
     * Get all categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get_categories_qrcode_checked(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $sort = $request->sort;
        $search = $request->search;
        $order = $sort=='updated_at' ? 'desc' : 'asc';
        $is_published = $request->is_published;
        $is_published = intval($is_published) == 1 || $is_published == null;

        $result = Category::where('checked_qrcode', 1)
            ->whereRaw($is_published ? 'published=1':'1')
            ->where(function ($query) use ($search) {
                $query->where('title', "LIKE", "%" . $search . "%")
                    ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->orderBy($sort, $order)
            ->offset(($page - 1) * $per_page)
            ->limit($per_page)
            ->with('tags')
            ->get();

        $total = Category::where('checked_qrcode', 1)
            ->whereRaw($is_published ? 'published=1' : '1')
            ->where(function ($query) use ($search) {
                $query->where('title', "LIKE", "%" . $search . "%")
                    ->orWhere('description', "LIKE", "%" . $search . "%");
            })
            ->orderBy($sort, $order)
            ->count();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/get_exercise_from_tag",
     *   tags={"Categories"},
     *   summary="GetCategories_QRCodeChecked",
     *   operationId="get_exercise_from_tag",
     *
     *   @OA\Parameter(
     *      name="tag_id",
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
    public function get_exercise_from_tag(Request $request) {
        $page = intval($request->page);
        $per_page = intval($request->per_page);
        $tag_id = $request->tag_id;

        $total = Tag::findOrFail($tag_id)->count_exercises();
        $result = Tag::findOrFail($tag_id)
            ->exercises()
            ->offset(($page - 1) * $per_page)
            ->limit($per_page)
            ->get();

        return response()->json([
            'data' => $result,
            'total' => $total,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page),
            'current_page' => $page,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/delete_categories",
     *   tags={"Categories"},
     *   summary="DeleteCategories",
     *   operationId="delete_categories",
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
     * Delete categories
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete_categories(Request $request) {
        $items = json_decode($request->selected_items);
        Category::whereIn('id', $items)->delete();

        return response()->json([
            'data' => $items,
        ]);
    }

    /**
     * @OA\Get(
     ** path="/api/delete_tag",
     *   tags={"Categories"},
     *   summary="DeleteTag",
     *   operationId="delete_tag",
     *
     *   @OA\Parameter(
     *      name="tag_id",
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
    public function delete_tag(Request $request) {
        $tag_id = $request->tag_id;
        Tag::findOrFail($tag_id)->delete();

        return response()->json([
            'data' => $tag_id,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/add_categories",
     *   tags={"Categories"},
     *   summary="AddCategories",
     *   operationId="add_categories",
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
    public function add_categories(Request $request) {
        $title = $request->get('title', '');
        $description = $request->get('description', '');
        $qr_code = intval($request->get('qr_code', 0));
        $tags = json_decode($request->get('tags'));
        $published = $request->get('published', 0);

        $filepath = '';
        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
        }

        $category = Category::create([
            'title' => $title,
            'description' => $description,
            'file' => $filepath,
            'checked_qrcode' => $qr_code ? '1' : '0',
            'published' => $published
        ]);


        foreach($tags as $tag) {
            $data = Tag::create([
                'name' => $tag->title,
                'has_subtags' => $tag->has_subtags
            ]);

            CategoryTag::create([
                'category_id' => $category->id,
                'tag_id' => $data->id
            ]);

            if($tag->has_subtags) {
                foreach($tag->subtags as $subtag) {
                    $stag = Tag::create([
                        'name' => $subtag->name,
                        'parent_id' => $data->id
                    ]);

                    foreach($subtag->exercises as $exercise) {
                        ExerciseTag::create([
                            'exercise_id' => $exercise->id,
                            'tag_id' => $stag->id
                        ]);
                    }
                }
            } else {
                foreach($tag->exercises as $exercise) {
                    ExerciseTag::create([
                        'exercise_id' => $exercise->id,
                        'tag_id' => $data->id
                    ]);
                }
            }


        }

        return response()->json([
            'title' => $title,
            'description' => $description,
        ]);
    }

    /**
     * @OA\Post(
     ** path="/api/update_categories",
     *   tags={"Categories"},
     *   summary="UpdateCategories",
     *   operationId="update_categories",
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
    public function update_categories(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title', '');
        $description = $request->get('description', '');
        $qr_code = intval($request->get('qr_code', 0));
        $tags = json_decode($request->get('tags'));
        $published = $request->get('published', 0);

        $filepath = '';

        $category = Category::findOrFail($id);

        $category->title = $title;
        $category->description = $description;
        $category->checked_qrcode = $qr_code;
        $category->published = $published;

        if ($request->file('file') && $request->file('file')->isValid()){
            $fileName = time().'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('assets/uploads'), $fileName);
            $filepath = 'assets/uploads/'.$fileName;
            $category->file = $filepath;
        }

        $category->touch();
        $category->save();

        CategoryTag::where('category_id', $id)->delete();

        foreach($tags as $tag) {
            $data = Tag::create([
                'name' => $tag->title,
                'has_subtags' => $tag->has_subtags
            ]);

            CategoryTag::create([
                'category_id' => $id,
                'tag_id' => $data->id
            ]);

            if($tag->has_subtags) {
                foreach($tag->subtags as $subtag) {
                    $stag = Tag::create([
                        'name' => $subtag->name,
                        'parent_id' => $data->id
                    ]);

                    foreach($subtag->exercises as $exercise) {
                        ExerciseTag::create([
                            'exercise_id' => $exercise->id,
                            'tag_id' => $stag->id
                        ]);
                    }
                }
            } else {
                foreach($tag->exercises as $exercise) {
                    ExerciseTag::create([
                        'exercise_id' => $exercise->id,
                        'tag_id' => $data->id
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
     ** path="/api/get_category_content",
     *   tags={"Categories"},
     *   summary="GetCategoryContent",
     *   operationId="get_category_content",
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
    public function get_category_content(Request $request) {
        $id = $request->id;
        $category = Category::with(["tags" => function ($query) {
            $query->with(['subtags' => function ($query) {
                    $query->with('exercises');
                }])->with('exercises');
        }])->findOrFail($id);
        $tags = $category->tags;

        return response()->json([
            'id' => $id,
            'data' => $category,
            'tags' => $tags
        ]);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Get the specified resources by filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function query(Request $request) {
        return datatables()->eloquent(Category::query())->toJson();
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
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json("Deleted");
    }
}
