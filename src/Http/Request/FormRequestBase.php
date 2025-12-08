<?php

namespace PhpHelpers\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use PhpHelpers\Helpers\ApiResponse;

class FormRequestBase extends FormRequest
{
  use ApiResponse;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
  {
    throw new \Illuminate\Validation\ValidationException(
      $validator,
      $this->errorResponse($validator->errors(), 'Validation error', 422),
    );
  }
}
