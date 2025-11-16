<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *   path="/guest",
 *   tags={"guests"},
 *   summary="Get all guests or get guest by email",
 *  @OA\Parameter(
 *     name="email",
 *     in="query",
 *     required=false,
 *     description="Email of the guest to filter by",
 *     @OA\Schema(type="string", format="email", example="john.doe@example.com")
 *  ),
 *   @OA\Response(
 *     response=200,
 *     description="List of guests or a single guest"
 *  )
 * )
 */

Flight::route('GET /guest', function(){
    $email = Flight::request()->query['email'] ?? null;

    if($email) {
        Flight::json(Flight::guestService()->getGuestByEmail($email));
    } else {
        Flight::json(Flight::guestService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/guest/{id}",
 *     tags={"guests"},
 *     summary="Get a single guest by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Guest ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Guest data"
 *     )
 * )
 */

Flight::route('GET /guest/@id', function($id){
    Flight::json(Flight::guestService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/guest",
 *     tags={"guests"},
 *     summary="Register a new guest",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "username", "password"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="dob", type="string", format="date", example="1990-05-10"),
 *             @OA\Property(property="gender", type="string", example="male"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="username", type="string", example="johnny90"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *             @OA\Property(property="tel_num", type="string", example="+38761123456"),
 *             @OA\Property(property="country", type="string", example="Bosnia and Herzegovina"),
 *             @OA\Property(property="city", type="string", example="Sarajevo"),
 *             @OA\Property(property="address", type="string", example="Zmaja od Bosne 12")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Guest successfully registered"
 *     )
 * )
 */

Flight::route('POST /guest', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::guestService()->registerGuest($data));
});


/**
 * @OA\Put(
 *     path="/guest/{id}",
 *     tags={"guests"},
 *     summary="Update an existing guest by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Guest ID to update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "username", "password"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="dob", type="string", format="date", example="1990-05-10"),
 *             @OA\Property(property="gender", type="string", example="male"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="username", type="string", example="johnny90"),
 *             @OA\Property(property="password", type="string", example="newSecret123"),
 *             @OA\Property(property="tel_num", type="string", example="+38761123456"),
 *             @OA\Property(property="country", type="string", example="Bosnia and Herzegovina"),
 *             @OA\Property(property="city", type="string", example="Sarajevo"),
 *             @OA\Property(property="address", type="string", example="Zmaja od Bosne 12")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Guest successfully updated"
 *     )
 * )
 */

Flight::route('PUT /guest/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::guestService()->update($id, $data));
});

/**
 * @OA\Patch(
 *     path="/guest/{id}",
 *     tags={"guests"},
 *     summary="Partially update a guest by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Guest ID to partially update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="first_name", type="string", example="Updated first name"),
 *             @OA\Property(property="last_name", type="string", example="Updated last name"),
 *             @OA\Property(property="dob", type="string", format="date", example="1991-01-01"),
 *             @OA\Property(property="gender", type="string", example="female"),
 *             @OA\Property(property="email", type="string", format="email", example="new.email@example.com"),
 *             @OA\Property(property="username", type="string", example="newUsername"),
 *             @OA\Property(property="password", type="string", example="optionalNewPass"),
 *             @OA\Property(property="tel_num", type="string", example="+38762111222"),
 *             @OA\Property(property="country", type="string", example="Croatia"),
 *             @OA\Property(property="city", type="string", example="Zagreb"),
 *             @OA\Property(property="address", type="string", example="Ilica 1")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Guest partially updated"
 *     )
 * )
 */

Flight::route('PATCH /guest/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::guestService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/guest/{id}",
 *     tags={"guests"},
 *     summary="Delete a guest by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Guest ID to delete",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Guest successfully deleted"
 *     )
 * )
 */

Flight::route('DELETE /guest/@id', function($id){
    Flight::json(Flight::guestService()->delete($id));
});
?>