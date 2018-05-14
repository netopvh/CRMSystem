<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
      return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'string|max:255',
      'address' => 'string|nullable',
      'phone' => 'string|max:17|nullable',  
      'filial_id' => 'integer|nullable',
      'department_id' => 'integer|nullable',
      'parent_id' => 'integer|nullable',

      'city_id' => 'integer|nullable',
      'name' => 'string|max:255|nullable',

      'first_item' => 'integer|max:1|nullable',
      'medium_item' => 'integer|max:1|nullable',

      'moderation' => 'integer|max:1|nullable',
      'system_item' => 'integer|max:1|nullable',   
    ];
  }
}
