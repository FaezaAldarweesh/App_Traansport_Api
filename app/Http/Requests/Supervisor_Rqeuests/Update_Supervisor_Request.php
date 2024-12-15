<?php

namespace App\Http\Requests\Supervisor_Rqeuests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Supervisor_Request extends FormRequest
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
        $supervisor_id = $this->route('supervisor');

        return [
            'name' => ['sometimes','nullable','regex:/^[\p{L}\s]+$/u','min:2','max:50 ',Rule::unique('supervisors', 'name')->ignore($supervisor_id)],
            'username' => ['sometimes','nullable','min:6','max:50',Rule::unique('supervisors', 'username')->ignore($supervisor_id)],
            'password' => 'sometimes|nullable|string|min:8',
            'location' => 'sometimes|nullable|string|min:5',
            'phone' => ['sometimes','nullable','min:10','max:10','regex:/^([0-9\s\-\+\(\)]*)$/',Rule::unique('supervisors', 'phone')->ignore($supervisor_id)],
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
            'name' => 'اسم المشرفة',
            'username' => 'اسم المستخدم',
            'password' => 'كلمة المرور',
            'location' => 'الموقع',
            'phone' => 'رقم الهاتف',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'name.regex' => 'يجب أن يحوي  :attribute على أحرف فقط',
            'name.min' => 'الحد الأدنى لطول :attribute على الأقل هو 2 حرف',
            'name.max' => 'الحد الأقصى لطول  :attribute هو 50 حرف',            
            'username.min' => 'الحد الأدنى لطول :attribute على الأقل هو 6 حرف',
            'username.max' => 'الحد الأقصى لطول :attribute على الأقل هو 50 حرف',
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'password.min' => 'الحد الأدنى لطول :attribute على الأقل هو 8 محرف',
            'phone.max' => 'الحد الأقصى لطول  :attribute هو 10 حرف',
            'phone.min' => 'الحد الأدنى لطول :attribute على الأقل هو 10 حرف',
            'phone.regex' => 'يجب أن يحوي  :attribute على أرقام فقط',
            'location.min' => 'الحد الأدنى لطول :attribute على الأقل هو 5 حرف',
        ];
    }
}
