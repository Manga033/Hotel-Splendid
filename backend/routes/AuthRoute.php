<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenApi\Annotations as OA;

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="Add new user",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"username","password"},
     *                 @OA\Property(property="username", type="string", example="demo", description="Username"),
     *                 @OA\Property(property="password", type="string", example="some_password", description="User password"),
     *                 @OA\Property(property="email", type="string", example="demo@example.com", description="Email")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User has been added."),
     *     @OA\Response(response=400, description="Validation failed."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("POST /register", function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ], 200);
        } else {
            if (isset($response['errors'])) {
                Flight::json([
                    'success' => false,
                    'message' => $response['error'],
                    'errors' => $response['errors']
                ], 400);
            } else {
                Flight::halt(500, $response['error']);
            }
        }
    });

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using username and password",
     *      @OA\Response(response=200, description="User data and JWT"),
     *      @OA\Response(response=400, description="Validation failed."),
     *      @OA\RequestBody(
     *          description="Login credentials",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","password"},
     *              @OA\Property(property="username", type="string", example="demo", description="Username"),
     *              @OA\Property(property="password", type="string", example="some_password", description="User password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ], 200);
        } else {
            if (isset($response['errors'])) {
                Flight::json([
                    'success' => false,
                    'message' => $response['error'],
                    'errors' => $response['errors']
                ], 400);
            } else {
                Flight::json([
                    'success' => false,
                    'message' => $response['error']
                ], 401);
            }
        }
    });

});
