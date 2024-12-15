<?php

namespace App\Http\Requests\Trip_Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Store_Trip_Request extends FormRequest
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
            'name' => 'required|string|in:delivery,school',
            'path_id' => 'required|integer|exists:paths,id',
            'bus_id' => 'required|integer|exists:buses,id',
            'students' => 'required|array',
            'students.*' => 'required|integer|exists:students,id',
            'supervisors' => 'required|array',
            'supervisors.*' => 'required|integer|exists:supervisors,id',
            'drivers' => 'required|array',
            'drivers.*' => 'required|integer|exists:drivers,id',
        ];
    }
    // //===========================================================================================================================
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
            'name' => 'اسم الرحلة',
            'type' => 'نوع الرحلة',
            'path_id' => 'اسم المسار',
            'bus_id' => 'اسم الباص',
            'status'=> 'حالة الرحلة',
            'student' => 'اسم الطالب',
            'supervisor' => 'اسم المشرفة',
            'driver' => 'اسم السائق',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'required' => ' :attribute مطلوب',
            'name.in' => 'يأخذ الحقل :attribute فقط القيم إما ( delivery أو school )',
            'type.in' => 'يأخذ الحقل :attribute فقط القيم إما ( go أو back )',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'path_id.exists' => ':attribute غير موجود , يجب أن يكون :attribute موجود ضمن المسارات المخزنة سابقا',
            'bus_id.exists' => ':attribute غير موجود , يجب أن يكون :attribute موجود ضمن الباصات المخزنة سابقا',
            'boolean' => ' يجب أن تكون :attribute  قيمتها إما 1 أو 0',
            'array' => 'يجب أن يكون :attribute من نمط مصفوفة',
            'trip.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'buses.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'students.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'supervisors.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
            'drivers.*.id.exists' => 'يجب أن يكون :attribute موجودا مسبقا',
        ];
    }
}
