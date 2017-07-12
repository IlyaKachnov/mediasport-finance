<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
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
            'name' => 'required|max:255|unique:users,name,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'required|min:6',
            'roles_list' => 'required'
        ];
    }
     public function messages()
        {
             return [
            'name.unique' => 'Данное имя пользователя уже используется',
            'email.unique' => 'Данный email уже используется',
        ];
        }

}
