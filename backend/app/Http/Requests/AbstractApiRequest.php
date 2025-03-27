<?php
declare(strict_types=1);
namespace App\Http\Requests;

use App\Interfaces\ApiRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractApiRequest extends FormRequest implements ApiRequestInterface
{

    /**
     * @return array|null
     */
    public function validationData(): ?array
    {
        if (!$this->ajax()) {
//            throw new \Exception('Is not ajax.');
        }

        return array_merge(
            $this->all(),
            $this->json()->all(),
            $this->route()->parameters()
        );
    }

    public function authorize(): bool
    {
        //TODO  добаить авторизацию
        return true;
    }

}
