<?php

namespace App\Services\ApiServices;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\BusTrip;
use App\Models\Student;
use App\Models\DriverTrip;
use App\Models\Supervisor;
use App\Models\StudentTrip;
use App\Models\SupervisorTrip;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\AllStudentsByTripTrait;

class TripService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait,AllStudentsByTripTrait;
    /**
     * method to view all Trips 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Trips(){
        try {
            $Trips = Trip::all();
            return $Trips;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche Trips', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new Trip
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Trip($data) {
        try {
            // التحقق من وجود رحلة توصيل بنفس المسار والباص
            if ($data['name'] === 'delivery') {
                $existingTripByPath = Trip::where('name', 'delivery')
                                          ->where('type', 'go')
                                          ->where('path_id', $data['path_id'])
                                          ->exists();
                if ($existingTripByPath) {
                    throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى مسبقاً');
                }
    
                $existingTripByBus = Trip::where('name', 'delivery')
                                         ->where('type', 'go')
                                         ->where('bus_id', $data['bus_id'])
                                         ->exists();
                if ($existingTripByBus) {
                    throw new \Exception('هذا الباص مرتبط برحلة توصيل أخرى مسبقاً');
                }
    
                $existingTrips = Trip::where('name', 'delivery')
                                     ->where('type', 'go')
                                     ->pluck('id');
    
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTrips)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    throw new \Exception('تم إضافة هذا الطالب إلى رحلة توصيل أخرى مسبقاً');
                }
    
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTrips)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    throw new \Exception('تم إضافة هذا المشرف إلى رحلة توصيل أخرى مسبقاً');
                }
    
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTrips)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    throw new \Exception('تم إضافة هذا السائق إلى رحلة توصيل أخرى مسبقاً');
                }

            }elseif($data['name'] === 'school'){
                //جلب كل الرحل المدرسية بتاريخ اليوم    
                $existingTripsToday = Trip::where('name', 'school')
                                          ->whereDate('created_at', now()->toDateString())
                                          ->pluck('id');
            
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTripsToday)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    throw new \Exception('تم إضافة هذا الطالب إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }
            
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTripsToday)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    throw new \Exception('تم إضافة هذا المشرف إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }
            
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTripsToday)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    throw new \Exception('تم إضافة هذا السائق إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }

                 // التحقق من الباص
                $existingBus = Trip::where('name', 'school')
                                    ->where('bus_id', $data['bus_id'])
                                    ->whereDate('created_at', now()->toDateString())
                                    ->exists();
                if ($existingBus) {
                    throw new \Exception('تم استخدام هذا الباص في رحلة مدرسية أخرى بتاريخ اليوم');
                }
            }
            

            $bus = Bus::find($data['bus_id']);

            if (count($data['students']) > $bus->number_of_seats) {
                throw new \Exception('عدد الطلاب يجب أن يساوي عدد مقاعد الباص ');
            }
    
            // إنشاء رحلتي ذهاب وإياب
            $tripTypes = ['go', 'back'];
            foreach ($tripTypes as $type) {
                $trip = new Trip();
                $trip->name = $data['name'];
                $trip->type = $type;
                $trip->path_id = $data['path_id'];
                $trip->bus_id = $data['bus_id'];
                $trip->save();
    
                $trip->students()->attach($data['students']);
                $trip->supervisors()->attach($data['supervisors']);
                $trip->drivers()->attach($data['drivers']);
                $trip->save();
            }
            return $trip;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create TRip', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  $data
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Trip($data, $id){
        try {
            $trip = Trip::find($id);
            if(!$trip){
                throw new \Exception('Trip not found');
            }
            
            // جلب رحلة الإياب المرتبطة (إذا كانت موجودة)
            $returnTrip = Trip::where('name', $trip->name)
                              ->where('type', $trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $trip->path_id)
                              ->where('bus_id', $trip->bus_id)
                              ->first();
    
            // التحقق من وجود رحلة بنفس المسار والباص
            if ($data['name'] === 'delivery') {
                $existingTripByPath = Trip::where('name', 'delivery')
                                          ->where('type', 'go')
                                          ->where('path_id', $data['path_id'])
                                          ->where('id', '!=', $id)
                                          ->exists();

                if ($existingTripByPath) {
                    throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى مسبقاً');
                }
    
                $existingTripByBus = Trip::where('name', 'delivery')
                                         ->where('type', 'go')
                                         ->where('bus_id', $data['bus_id'])
                                         ->where('id', '!=', $id)
                                         ->exists();
                if ($existingTripByBus) {
                    throw new \Exception('هذا الباص مرتبط برحلة توصيل أخرى مسبقاً');
                }
    
                $existingTrips = Trip::where('name', 'delivery')
                                     ->where('type', 'go')
                                     ->where('id', '!=', $id)
                                     ->pluck('id');
    
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTrips)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    throw new \Exception('تم إضافة هذا الطالب إلى رحلة توصيل أخرى مسبقاً');
                }
    
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTrips)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    throw new \Exception('تم إضافة هذا المشرف إلى رحلة توصيل أخرى مسبقاً');
                }
    
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTrips)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    throw new \Exception('تم إضافة هذا السائق إلى رحلة توصيل أخرى مسبقاً');
                }
            }elseif($data['name'] === 'school'){
                //جلب كل الرحل المدرسية بتاريخ اليوم    
                $existingTripsToday = Trip::where('name', 'school')
                                          ->whereDate('created_at', now()->toDateString())
                                          ->pluck('id');
            
                // التحقق من الطالب
                $existingStudent = StudentTrip::whereIn('trip_id', $existingTripsToday)
                                              ->where('student_id', $data['students'])
                                              ->exists();
                if ($existingStudent) {
                    throw new \Exception('تم إضافة هذا الطالب إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }
            
                // التحقق من المشرف
                $existingSupervisor = SupervisorTrip::whereIn('trip_id', $existingTripsToday)
                                                    ->where('supervisor_id', $data['supervisors'])
                                                    ->exists();
                if ($existingSupervisor) {
                    throw new \Exception('تم إضافة هذا المشرف إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }
            
                // التحقق من السائق
                $existingDriver = DriverTrip::whereIn('trip_id', $existingTripsToday)
                                            ->where('driver_id', $data['drivers'])
                                            ->exists();
                if ($existingDriver) {
                    throw new \Exception('تم إضافة هذا السائق إلى رحلة مدرسية أخرى بتاريخ اليوم');
                }

                 // التحقق من الباص
                $existingBus = Trip::where('name', 'school')
                                    ->where('bus_id', $data['bus_id'])
                                    ->whereDate('created_at', now()->toDateString())
                                    ->exists();
                if ($existingBus) {
                    throw new \Exception('تم استخدام هذا الباص في رحلة مدرسية أخرى بتاريخ اليوم');
                }
            }
    
            $bus = Bus::find($data['bus_id']);

            if (count($data['students']) > $bus->number_of_seats) {
                throw new \Exception('عدد الطلاب يجب أن يساوي عدد مقاعد الباص ');
            }

            // تحديث معلومات رحلة الذهاب
            $trip->update([
                'name' => $data['name'],
                'path_id' => $data['path_id'],
                'bus_id' => $data['bus_id']
            ]);
    
            // تحديث علاقات رحلة الذهاب
            $trip->students()->sync($data['students']);
            $trip->supervisors()->sync($data['supervisors']);
            $trip->drivers()->sync($data['drivers']);
    
            // تحديث رحلة الإياب إذا كانت موجودة
            if ($returnTrip) {
                $returnTrip->update([
                    'name' => $data['name'],
                    'type' => $returnTrip->type,
                    'path_id' => $data['path_id'],
                    'bus_id' => $data['bus_id']
                ]);
    
                // تحديث علاقات رحلة الإياب
                $returnTrip->students()->sync($data['students']);
                $returnTrip->supervisors()->sync($data['supervisors']);
                $returnTrip->drivers()->sync($data['drivers']);
            }
    
            return $trip;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_Trip($Trip_id)
    {
        try {  
            $Trip = Trip::find($Trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }

            $returnTrip = Trip::where('name', $Trip->name)
                              ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $Trip->path_id)
                              ->where('bus_id', $Trip->bus_id)
                              ->first();
            
            $Trip->students()->updateExistingPivot($Trip->students->pluck('id'), ['deleted_at' => now()]);     
            $Trip->supervisors()->updateExistingPivot($Trip->supervisors->pluck('id'), ['deleted_at' => now()]);     
            $Trip->drivers()->updateExistingPivot($Trip->drivers->pluck('id'), ['deleted_at' => now()]);  
            
            $returnTrip->students()->updateExistingPivot($returnTrip->students->pluck('id'), ['deleted_at' => now()]);     
            $returnTrip->supervisors()->updateExistingPivot($returnTrip->supervisors->pluck('id'), ['deleted_at' => now()]);     
            $returnTrip->drivers()->updateExistingPivot($returnTrip->drivers->pluck('id'), ['deleted_at' => now()]);  

            $Trip->delete();
            $returnTrip->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Trip
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Trip()
    {
        try {  
            $Trip = Trip::onlyTrashed()->get();
            return $Trip;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed Trip', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Trip alraedy exist
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_Trip($Trip_id)
    {
        try {
            $Trip = Trip::onlyTrashed()->findOrFail($Trip_id);
    
            $returnTrip = Trip::withTrashed()
                              ->where('name', $Trip->name)
                              ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $Trip->path_id)
                              ->where('bus_id', $Trip->bus_id)
                              ->first();
    
            // استعادة الرحلة الأصلية
            $Trip->restore();
    
            // استعادة الرحلة العكسية إن وُجدت
            if ($returnTrip) {
                $returnTrip->restore();
    
                // تحديث العلاقات الخاصة بالرحلة العكسية
                $returnTrip->students()->withTrashed()->updateExistingPivot($returnTrip->students->pluck('id'), ['deleted_at' => null]);
                $returnTrip->supervisors()->withTrashed()->updateExistingPivot($returnTrip->supervisors->pluck('id'), ['deleted_at' => null]);
                $returnTrip->drivers()->withTrashed()->updateExistingPivot($returnTrip->drivers->pluck('id'), ['deleted_at' => null]);
            }
    
            // تحديث العلاقات الخاصة بالرحلة الأصلية
            $Trip->students()->withTrashed()->updateExistingPivot($Trip->students->pluck('id'), ['deleted_at' => null]);
            $Trip->supervisors()->withTrashed()->updateExistingPivot($Trip->supervisors->pluck('id'), ['deleted_at' => null]);
            $Trip->drivers()->withTrashed()->updateExistingPivot($Trip->drivers->pluck('id'), ['deleted_at' => null]);
    
            return true;
    
        } catch (\Exception $e) {Log::error($e->getMessage());return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) {Log::error($th->getMessage());return $this->failed_Response($th->getMessage(), 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Trip that soft deleted before
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_Trip($Trip_id)
    {   
        try {
            $Trip = Trip::onlyTrashed()->find($Trip_id);
            if (!$Trip) {
                throw new \Exception('Trip not found');
            }
            
            // البحث عن الرحلة العكسية مع تضمين السجلات المحذوفة
            $returnTrip = Trip::withTrashed()
                ->where('name', $Trip->name)
                ->where('type', $Trip->type === 'go' ? 'back' : 'go')
                ->where('path_id', $Trip->path_id)
                ->where('bus_id', $Trip->bus_id)
                ->first();
            
            // حذف الرحلة الأصلية نهائيًا
            $Trip->forceDelete();
            
            // حذف الرحلة العكسية نهائيًا إن وُجدت
            if ($returnTrip) {
                $returnTrip->forceDelete();
            }
            
            return true;            
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Trip', 400);}
    }
    //========================================================================================================================


    

    












    //========================================================================================================================
    public function update_trip_status($data,$trip_id)
    {
        try {
            $Trip = Trip::find($trip_id);
            if(!$Trip){
                throw new \Exception('Trip not found');
            }
            $Trip->status = $data['status'] ?? $Trip->status;
            $Trip->save(); 

            return $Trip;

        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetching Trip', 400);}
    }
    //========================================================================================================================


}
