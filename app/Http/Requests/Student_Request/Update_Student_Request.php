<?php

namespace App\Http\Requests\Student_Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Student_Request extends FormRequest
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
            'name' => 'sometimes|nullable|unique:students,name,' . $this->route('student') . '|regex:/^[\p{L}\s]+$/u|min:2|max:50',
            'father_phone' => 'sometimes|nullable|min:10|max:10|string',
            'mather_phone' => 'sometimes|nullable|min:10|max:10|string',
            'longitude'   => 'sometimes|nullable|numeric|between:-180,180',
            'latitude'    => 'sometimes|nullable|numeric|between:-90,90',
            'user_id' => 'sometimes|nullable|integer|exists:users,id',
            'status' => 'sometimes|nullable|string|in:attendee,absent_all,absent_go,absent_back,transported',
        ];
    }
    //===========================================================================================================================
    // protected function failedValidation(Validator $validator){
    //     throw new HttpResponseException(response()->json([
    //         'status' => 'error 422',
    //         'message' => 'فشل التحقق يرجى التأكد من المدخلات',
    //         'errors' => $validator->errors(),
    //     ]));
    // }
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
            'name' => 'اسم الطالب',
            'father_phone' => 'رقم الأب',
            'mather_phone' => 'رقم الأم',
            'longitude' => 'خط الطول',
            'latitude' => 'خط العرض',
            'user_id' => 'اسم الأب',
            'status'=> 'حالة الطالب',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'name.regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'name.max' => 'الحد الأقصى لطول  :attribute هو 50 حرف',
            'name.min' => 'الحد الأدنى لطول :attribute على الأقل هو 2 حرف',
            'max' => 'الحد الأقصى لطول  :attribute هو 10 حرف',
            'min' => 'الحد الأدنى لطول :attribute على الأقل هو 10 حرف',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'numeric' => 'يجب أن يكون :attribute رقماً',
            'latitude.between'  => ':attribute يجب أن يكون بين -90 و 90',
            'longitude.between'  => ':attribute يجب أن يكون بين -180 و 180',
            'in' => 'يجب أن تكون قيمة الحقل :attribute إحدى القيم التالية : attendee,absent_all,absent_go,absent_back,transported',
        ];
    }
}
