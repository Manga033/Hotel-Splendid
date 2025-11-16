<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Hotel Splendid API",
 *     description="API for managing guests, rooms, bookings and reviews",
 *     version="1.0",
 *     @OA\Contact(
 *         email="danin.mangafic@stu.ibu.edu.ba",
 *         name="Hotel Splendid – Web Programming"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost/Hotel-Splendid/Hotel-Splendid/backend",
 *     description="Local API server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */
