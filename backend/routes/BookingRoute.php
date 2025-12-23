<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *    path="/booking",
 *    tags={"bookings"},
 *    summary="Get all bookings or orded by creation date",
 *    security={{"ApiKey": {}}},
 *    @OA\Parameter(
 *       name="order",
 *       in="query",
 *       required=false,
 *       description="Order by creation date if 'created_at' is provided",
 *       @OA\Schema(
 *        type="string",
 *        enum={"created_at"})
 *      ),
 * @OA\Response(
 *     response=200,
 *     description="List of bookings"
 *   )
 * )
 */

Flight::route('GET /booking', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $user = Flight::get('user');
    $order = Flight::request()->query['order'] ?? null;

    if ($user->role === 'admin') { 
        if($order === 'created_at') {
            Flight::json(Flight::bookingService()->listBookingsByCreatedAt());
        } else {
            Flight::json(Flight::bookingService()->getAll());
        }
    } else {
        $myBookings = Flight::bookingService()->getBookingsByGuestId($user->id);
        Flight::json($myBookings);
    }
});

/**
 * @OA\Get(
 *     path="/booking/{id}",
 *     tags={"bookings"},
 *     summary="Get a single booking by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking data"
 *     )
 * )
 */

Flight::route('GET /booking/@id', function($id){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::bookingService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/booking",
 *     tags={"bookings"},
 *     summary="Create a new booking",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"guest_id", "check_in_date", "check_out_date"},
 *             @OA\Property(property="guest_id", type="integer", example=1),
 *             @OA\Property(property="check_in_date", type="string", format="date", example="2025-05-01"),
 *             @OA\Property(property="check_out_date", type="string", format="date", example="2025-05-05"),
 *             @OA\Property(property="num_of_guests", type="integer", example=2),
 *             @OA\Property(property="num_of_children", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="standard"),
 *             @OA\Property(property="total_price", type="number", format="float", example=350.00),
 *             @OA\Property(property="status", type="string", example="pending")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking created"
 *     )
 * )
 */

Flight::route('POST /booking', function(){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingService()->createBooking($data));
});

/**
 * @OA\Put(
 *     path="/booking/{id}",
 *     tags={"bookings"},
 *     summary="Update an existing booking",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"guest_id", "check_in_date", "check_out_date", "num_of_guests", "num_of_children", "type", "total_price", "status"},
 *             @OA\Property(property="guest_id", type="integer", example=1),
 *             @OA\Property(property="check_in_date", type="string", format="date", example="2025-05-01"),
 *             @OA\Property(property="check_out_date", type="string", format="date", example="2025-05-05"),
 *             @OA\Property(property="num_of_guests", type="integer", example=2),
 *             @OA\Property(property="num_of_children", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="suite"),
 *             @OA\Property(property="total_price", type="number", format="float", example=500.00),
 *             @OA\Property(property="status", type="string", example="confirmed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking updated"
 *     )
 * )
 */

Flight::route('PUT /booking/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingService()->update($id, $data));
});

/**
 * @OA\Patch(
 *     path="/booking/{id}",
 *     tags={"bookings"},
 *     summary="Partially update a booking",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="guest_id", type="integer", example=1),
 *             @OA\Property(property="check_in_date", type="string", format="date", example="2025-05-02"),
 *             @OA\Property(property="check_out_date", type="string", format="date", example="2025-05-06"),
 *             @OA\Property(property="num_of_guests", type="integer", example=3),
 *             @OA\Property(property="num_of_children", type="integer", example=0),
 *             @OA\Property(property="type", type="string", example="deluxe"),
 *             @OA\Property(property="total_price", type="number", format="float", example=420.00),
 *             @OA\Property(property="status", type="string", example="cancelled")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking partially updated"
 *     )
 * )
 */

Flight::route('PATCH /booking/@id', function($id){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/booking/{id}",
 *     tags={"bookings"},
 *     summary="Delete a booking",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking deleted"
 *     )
 * )
 */

Flight::route('DELETE /booking/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::bookingService()->delete($id));
});