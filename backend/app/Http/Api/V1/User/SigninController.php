<?php
declare(strict_types=1);
namespace App\Http\Api\V1\User;

use App\Http\Api\V1\AbstractController;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

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
}
