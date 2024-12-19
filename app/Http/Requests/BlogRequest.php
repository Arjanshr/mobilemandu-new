<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'=>['required'],
            'content'=>['required'],
            'image'=>['mimes:jpeg,png,jpg,gif,svg,ico,pdf','max:2048'],
            'status'=>['required']
        ];
    }

    protected function passedValidation()
    {
        if ($this->hasFile('image')) {
            $image_name = rand(0, 99999) . time() . '.' . $this->image->extension();
            $this->image->move(storage_path('app/public/blogs'), $image_name);
            $this['image']->file_name = $image_name;
        }
    }
}
