<?php

namespace App\Http\Requests\Bus_Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Bus_Request extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes','nullable','string','min:4','max:50', 'unique:buses,name,' . $this->route('bus')],
            'number_of_seats' => 'sometimes|nullable|integer|min:20',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'name' => 'اسم الباص',
            'number_of_seats' => 'عدد المقاعد',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'max' => 'الحد الأقصى لطول  :attribute هو 50 حرف',
            'name.min' => 'الحد الأدنى لطول :attribute على الأقل هو 4 حرف',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'number_of_seats.min' => 'الحد الأدنى لطول :attribute على الأقل هو 20 مقعد',
        ];
    }
}
