<?php

namespace Modules\Hr\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model  implements HasMedia
{
    public $types = [
        'Loan_payment_task' => 'مهمة سداد القرض',
        'Payroll_payment_task' => 'مهمة سداد المرتبات',
        'Employee_gosi_registration_task' => 'مهمة تسجيل تأمينات الموظفين في القوى العاملة',
        'Gosi_salary_updation_task' => 'مهمة تحديث رواتب التأمينات الاجتماعية',
        'Expense_claim_payment_task' => 'مهمة سداد مصاريف المصروفات',
        'Vacation_Settlement_payment_task' => 'مهمة تسوية إجازة وسدادها',
        'Final_settlement_payment_task' => 'مهمة سداد التسوية النهائية',
        'Exit_Reentry_payment_task' => 'مهمة سداد خروج وعودة',
        'Ticket_payment_task' => 'مهمة سداد تذكرة',
        'Ticket_issuing_task' => 'مهمة إصدار تذكرة',
        'Business_trip_payment_task' => 'مهمة سداد رحلة عمل',
        'Letter_issuing_task' => 'مهمة إصدار خطاب',
        'Asset_allocation_task' => 'مهمة تخصيص الأصول',
        'Asset_deallocation_task' => 'مهمة إلغاء تخصيص الأصول',
    ];

    use HasFactory, InteractsWithMedia;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public static function getTypeName($type)
    {
        $task = new self();

        if (array_key_exists($type, $task->types)) {
            return $task->types[$type];
        }

        return null; // Or you can return a default value or handle it as needed
    }

    public function scopeType(Builder $builder,$type){

        return $builder->when($type, function ($builder) use ($type) {
            $builder->where('type', $type);
        });
    }

    public function scopegetEmployee(Builder $builder, $employee){
        return $builder->when($employee, function ($builder) use ($employee) {
            $builder->where('employee_id', $employee);
        });
    }

    public function scopegetByDate(Builder $builder, $date){

        return $builder->when($date, function ($builder) use ($date) {
            $builder->whereDate('created_at', $date);
        });
    }
    public function scopeSearch(Builder $query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query
                ->where('status', 'like', '%' . $term . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $term . '%')
                ->orWhereRelation('employee', 'ar_username', 'like', '%' . $term . '%')
                ->orWhereRelation('employee', 'en_username', 'like', '%' . $term . '%');
        });
    }
}
