<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *   path="/booking-rooms",
 *   tags={"booking-rooms"},
 *   summary="Get all booking rooms or order them by nightly rate",
 *   @OA\Parameter(
 *    name="order",
 *    in="query",
 *    required=false,
 *    description="Order by nightly rate if 'nightly_rate' is provided",
 *    @OA\Schema(
 *     type="string",
 *     enum={"nightly_rate"})
 *   ),
 * @OA\Response(
 *     response=200,
 *     description="List of booking rooms"
 *   )
 * )
 */

Flight::route('GET /booking-rooms', function(){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);

    $order = Flight::request()->query['order'] ?? null;

    if($order === 'nightly_rate') {
        Flight::json(Flight::bookingRoomsService()->listBookingRoomsByNightlyRate());
    } else {
        Flight::json(Flight::bookingRoomsService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/booking-rooms/{id}",
 *     tags={"booking-rooms"},
 *     summary="Get a single booking room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking Room data"
 *     )
 * )
 */

Flight::route('GET /booking-rooms/@id', function($id){

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    Flight::json(Flight::bookingRoomsService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/booking-rooms",
 *     tags={"booking-rooms"},
 *     summary="Add a room to a booking",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"booking_id","room_id"},
 *             @OA\Property(property="booking_id", type="integer", example=1),
 *             @OA\Property(property="room_id", type="integer", example=101),
 *             @OA\Property(property="nightly_rate", type="number", format="float", example=150.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room added to booking successfully"
 *     )
 * )
 */

Flight::route('POST /booking-rooms', function(){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingRoomsService()->addRoomToBooking($data));
});

/**
 * @OA\Put(
 *     path="/booking-rooms/{id}",
 *     tags={"booking-rooms"},
 *     summary="Update a booking room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"booking_id","room_id", "nightly_rate"},
 *             @OA\Property(property="booking_id", type="integer", example=1),
 *             @OA\Property(property="room_id", type="integer", example=101),
 *             @OA\Property(property="nightly_rate", type="number", format="float", example=160.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking room updated successfully"
 *     )
 * )
 */

Flight::route('PUT /booking-rooms/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingRoomsService()->update($id, $data));
});

/**
 * @OA\Patch(
 *     path="/booking-rooms/{id}",
 *     tags={"booking-rooms"},
 *     summary="Partially update a booking room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="booking_id", type="integer", example=1),
 *             @OA\Property(property="room_id", type="integer", example=102),
 *             @OA\Property(property="nightly_rate", type="number", format="float", example=170.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking room partially updated successfully"
 *     )
 * )
 */

Flight::route('PATCH /booking-rooms/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingRoomsService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/booking-rooms/{id}",
 *     tags={"booking-rooms"},
 *     summary="Delete a booking room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Booking Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking room deleted successfully"
 *     )
 * )
 */

Flight::route('DELETE /booking-rooms/@id', function($id){

    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::bookingRoomsService()->delete($id));
});
?>