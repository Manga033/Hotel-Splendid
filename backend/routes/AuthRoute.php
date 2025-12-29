<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 * path="/auth/register",
 * summary="Register new user.",
 * description="Add a new user to the database.",
 * tags={"auth"},
 * @OA\RequestBody(
 * description="Add new user",
 * required=true,
 * @OA\MediaType(
 * mediaType="application/json",
 * @OA\Schema(
 * required={"username","password"},
 * @OA\Property(property="username", type="string", example="demo", description="Username"),
 * @OA\Property(property="password", type="string", example="some_password", description="User password"),
 * @OA\Property(property="email", type="string", example="demo@example.com", description="Email")
 * )
 * )
 * ),
 * @OA\Response(response=200, description="User has been added."),
 * @OA\Response(response=400, description="Validation failed."),
 * @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('POST /auth/register', function () {
    $data = Flight::request()->data->getData();
    try {
        $response = Flight::authService()->register($data);
        Flight::json($response);
    } catch (Exception $e) {
        Flight::json(['message' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Post(
 * path="/auth/login",
 * tags={"auth"},
 * summary="Login to system",
 * description="Login with username and password",
 * @OA\Response(response=200, description="User data and JWT"),
 * @OA\Response(response=400, description="Validation failed."),
 * @OA\RequestBody(
 * description="Login credentials",
 * required=true,
 * @OA\JsonContent(
 * required={"username","password"},
 * @OA\Property(property="username", type="string", example="demo", description="Username"),
 * @OA\Property(property="password", type="string", example="some_password", description="User password")
 * )
 * )
 * )
 */
Flight::route('POST /auth/login', function() {
    $data = Flight::request()->data->getData();
    $response = Flight::authService()->login($data);

    if (isset($response['success']) && $response['success']) {
        Flight::json([
            'message' => 'User logged in successfully',
            'data' => $response['data'] ?? $response
        ], 200);
    } else {
        Flight::json([
            'success' => false,
            'message' => $response['error'] ?? 'Login failed',
            'errors' => $response['errors'] ?? null
        ], 400);
    }
});
?>