<?php
declare(strict_types=1);
namespace App\Http\Api\V1;

use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *      title="Booking. Frontend API.",
 *      version="1.0.0",
 *      description="Booking Frontend API Swagger OpenApi",
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer",
 * )
 */
abstract class AbstractController extends Controller
{
}
