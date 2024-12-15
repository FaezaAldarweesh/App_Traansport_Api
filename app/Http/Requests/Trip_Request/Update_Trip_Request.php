<?php

namespace App\Http\Requests\Trip_Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Update_Trip_Request extends FormRequest
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
            'name' => 'sometimes|nullable|string|in:delivery,school',
            'path_id' => 'sometimes|nullable|integer|exists:paths,id',
            'bus_id' => 'sometimes|nullable|integer|exists:buses,id',
            'status' => 'sometimes|nullable|boolean',
            'students' => 'sometimes|nullable|array',
            'students.*' => 'sometimes|nullable|integer|exists:students,id',
            'supervisors' => 'sometimes|nullable|array',
            'supervisors.*' => 'sometimes|nullable|integer|exists:supervisors,id',
            'drivers' => 'sometimes|nullable|array',
            'drivers.*' => 'sometimes|nullable|integer|exists:drivers,id',
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
