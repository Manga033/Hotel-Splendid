<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *   path="/review",
 *   tags={"reviews"},
 *   summary="Get all reviews or order them by rating",
 *   security={{"ApiKey": {}}},
 *   @OA\Parameter(
 *     name="order",
 *     in="query",
 *   required=false,
 *   description="Order by rating if 'rating' is provided",  
 *   @OA\Schema(
 *     type="string",
 *     enum={"rating"})
 *  ),
 *   @OA\Response(
 *     response=200,
 *     description="List of reviews"
 *  )
 * )
 */

Flight::route('GET /review', function() {

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $order = Flight::request()->query['order'] ?? null;

    if($order === 'rating') {
        Flight::json(Flight::reviewService()->listReviewsByRating());
    } else {
        Flight::json(Flight::reviewService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/review/{id}",
 *     tags={"reviews"},
 *     summary="Get a single review by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review data"
 *     )
 * )
 */

Flight::route('GET /review/@id', function($id){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::reviewService()->getById($id));
});

/**
 * @OA\Post(
 *   path="/review",
 *   tags={"reviews"},
 *   summary="Create a new review",
 *   security={{"ApiKey": {}}},
 *  @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(
 *      required={"guest_id","rating", "title"},
 *      @OA\Property(property="guest_id", type="integer", example=1),
 *      @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *      @OA\Property(property="title", type="string", example="Great stay!"),
 *      @OA\Property(property="comment", type="string", example="I had a wonderful experience at the hotel.")
 *    )
 * ),
 *  @OA\Response(
 *   response=200,
 *   description="Review created successfully"
 *  ),
 *  @OA\Response(
 *   response=400,
 *   description="Validation failed"
 *  )
 * )
 */
Flight::route('POST /review', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data->getData();
    
    try {
        $result = Flight::reviewService()->createReview($data);
        Flight::json([
            'success' => true,
            'message' => 'Review created successfully',
            'data' => $result
        ], 200);
    } catch (Exception $e) {
        $errorData = json_decode($e->getMessage(), true);
        if (is_array($errorData) && isset($errorData['validation_failed'])) {
            Flight::json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorData['errors']
            ], 400);
        } else {
            Flight::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
});

/**
 * @OA\Put(
 *     path="/review/{id}",
 *     tags={"reviews"},
 *     summary="Update an existing review",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"guest_id","rating", "title", "comment"},
 *             @OA\Property(property="guest_id", type="integer", example=1),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4),
 *             @OA\Property(property="title", type="string", example="Good stay"),
 *             @OA\Property(property="comment", type="string", example="The stay was pleasant overall.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated successfully"
 *     )
 * )
 */

Flight::route('PUT /review/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Patch(
 *     path="/review/{id}",
 *     tags={"reviews"},
 *     summary="Partially update a review",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=3),
 *             @OA\Property(property="title", type="string", example="Average stay"),
 *             @OA\Property(property="comment", type="string", example="It was okay, nothing special.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review partially updated successfully"
 *     )
 * )
 */

Flight::route('PATCH /review/@id', function($id){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/review/{id}",
 *     tags={"reviews"},
 *     summary="Delete a review.",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Review ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     )
 * )
 */

Flight::route('DELETE /review/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::reviewService()->delete($id));
});
?>