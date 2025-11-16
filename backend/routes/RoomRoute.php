<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *   path="/room",
 *   tags={"rooms"},
 *   summary="Get all rooms or get rooms by status",
 *  @OA\Parameter(  
 *    name="status",
 *    in="query",
 *    required=false,
 *    description="Filter rooms by status",
 *  @OA\Schema(type="string", enum={"available", "occupied", "maintenance"}, example="available")
 * ),
 *  @OA\Response(
 *   response=200,
 *   description="List of rooms or filtered rooms"
 * )
 * )
 */

Flight::route('GET /room', function(){
    $status = Flight::request()->query['status'] ?? null;

    if($status) {
        Flight::json(Flight::roomService()->getByStatus($status));
    } else {
        Flight::json(Flight::roomService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/room/{id}",
 *     tags={"rooms"},
 *     summary="Get a single room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Room ID",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room data"
 *     )
 * )
 */

Flight::route('GET /room/@id', function($id){
    Flight::json(Flight::roomService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/room",
 *     tags={"rooms"},
 *     summary="Create a new room",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"room_number"},
 *             @OA\Property(property="room_number", type="integer", example=101),
 *             @OA\Property(property="floor", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="single"),
 *             @OA\Property(property="base_price", type="number", format="float", example=99.99),
 *             @OA\Property(property="description", type="string", example="A cozy single room"),
 *             @OA\Property(property="status", type="string", enum={"available", "occupied", "maintenance"}, example="available")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room created successfully"
 *     )
 * )
 */

Flight::route('POST /room', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::roomService()->createRoom($data));
});

/**
 * @OA\Put(
 *     path="/room/{id}",
 *     tags={"rooms"},
 *     summary="Update a room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"room_number", "floor", "type", "base_price", "description", "status"},
 *             @OA\Property(property="room_number", type="integer", example=101),
 *             @OA\Property(property="floor", type="integer", example=1),
 *             @OA\Property(property="type", type="string", example="double"),
 *             @OA\Property(property="base_price", type="number", format="float", example=120.00),
 *             @OA\Property(property="description", type="string", example="Updated description for the room."),
 *             @OA\Property(property="status", type="string", enum={"available", "occupied", "maintenance"}, example="occupied")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room updated successfully"
 *     )
 * )
 */

Flight::route('PUT /room/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::roomService()->update($id, $data));
});

/**
 * @OA\Patch(
 *     path="/room/{id}",
 *     tags={"rooms"},
 *     summary="Partially update a room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="room_number", type="integer", example=101),
 *             @OA\Property(property="floor", type="integer", example=2),
 *             @OA\Property(property="type", type="string", example="suite"),
 *             @OA\Property(property="base_price", type="number", format="float", example=170.00),
 *             @OA\Property(property="description", type="string", example="Now upgraded to a suite room"),
 *             @OA\Property(property="status", type="string", enum={"available", "occupied", "maintenance"}, example="maintenance")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room partially updated"
 *     )
 * )
 */

Flight::route('PATCH /room/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::roomService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/room/{id}",
 *     tags={"rooms"},
 *     summary="Delete a room by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Room ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room deleted successfully"
 *     )
 * )
 */

Flight::route('DELETE /room/@id', function($id){
    Flight::json(Flight::roomService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/room/{id}/availability",
 *     tags={"rooms"},
 *     summary="Check room availability by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Room ID to check availability",
 *         @OA\Schema(type="integer", example=101)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Room availability status"
 *     )
 * )
 */

Flight::route('GET /room/@id/availability', function($id) {
    $available = Flight::roomService()->isRoomAvailable($id);
    Flight::json(['room_id' => $id, 'available' => $available]);
});
?>