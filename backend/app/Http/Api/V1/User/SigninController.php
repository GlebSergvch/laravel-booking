<?php
declare(strict_types=1);
namespace App\Http\Api\V1\User;

use App\Http\Api\V1\AbstractController;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SigninController extends AbstractController
{

    public function __construct(
//        private readonly SignService $signService,
    ) {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/signin",
     *      summary="Авторизация",
     *      tags={"User"},
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Content",
     *           @OA\JsonContent(
     *              type="object",
     *              @OA\Property (
     *                  property="message",
     *                  type="string",
     *              ),
     *              @OA\Property (
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property (
     *                      property="phone",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string"
     *                       )
     *                  ),
     *                  @OA\Property (
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string"
     *                      )
     *                  ),
     *              ),
     *          ),
     *      ),
     *       @OA\Response(
     *             response=401,
     *             description="Unautorized",
     *             @OA\JsonContent(
     *                type="object",
     *                @OA\Property (
     *                    property="message",
     *                    type="string",
     *                ),
     *                @OA\Property (
     *                    property="errors",
     *                    type="object",
     *                ),
     *            ),
     *       ),
     * )
     *
     * @return JsonResponse
     */
    public function __invoke($request): JsonResponse
    {
//        $dto = new UserDto(...
//            array_merge($request->only(
//                'phone',
//                'password',
//            ), [
//                'token' => $request->bearerToken(),
//                'mode'  => 'login'
//            ]));

        return response()->json(true);
    }


    /**
     * @OA\Post(
     *      path="/api/v1/register",
     *      summary="Регистрация пользователя",
     *      tags={"User"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="John Doe"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  format="email",
     *                  example="john@example.com"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  format="password",
     *                  example="secret123"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Успешная регистрация",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="abcdef123456"),
     *              @OA\Property(property="token_type", type="string", example="Bearer")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(type="string", example="The email field is required.")
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(type="string", example="The password must be at least 6 characters.")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Internal server error")
     *          )
     *      )
     * )
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Авторизация (логин)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Выход (отзыв токена)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
