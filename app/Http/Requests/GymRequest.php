<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GymRequest extends FormRequest
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
    public function rules() {
        $id = NULL;
        if ($this->method() == "PATCH") {
            $id = $this->segment(2);
        }
       
        return [
            'name' => 'required|max:255|unique:gyms,name,' . $id,
        ];
    }
     public function messages()
        {
             return [
            'name.unique' => 'Данное название уже используется',
        ];
        }
}
