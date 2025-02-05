<?php

namespace App\Http\Requests;

use App\Models\Slider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SliderRequest extends FormRequest
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
            'title'=>['nullable','string'],
            'link_url'=>['nullable','url'],
            'display_order'=>['required','numeric','min:1',],
            'image'=>['nullable','mimes:jpeg,png,jpg,gif,svg,ico,pdf','max:2048'],
        ];
    }

    protected function passedValidation()
    {
        if ($this->hasFile('image')) {
            $image_name = rand(0, 99999) . time() . '.' . $this->image->extension();
            $this->image->move(storage_path('app/public/sliders'), $image_name);
            $this['image']->file_name = $image_name;
        }
    }
}
