<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    shifts?: PaginatedData<{
        id: number | string;
        name: string;
        start_time: string;
        end_time: string;
        created_at: string;
        created_by: string;
        updated_by: string;
        next_shift_id: number | string;
        shift_times?: {
            id: number | string;
            start_time: string;
            end_time: string;
            day_of_week: number | string;

        }
    }>;
    all_shifts?: {
        id: number | string;
        name: string;
    }
    times?: {
        id: number | string;
        name: string;
        start_time: string;
        end_time: string;
    }
    employees?: {
        id: number | string;
        name: string;
    }


}>();
console.log(props.all_shifts);
const breadcrumbs: BreadCrumbs = [
    {
        title: "الدوامات",
        href: route("shifts.index"),
    },
];

const formId = "shift-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.shifts,
    ),
    started_at: undefined,
    ended_at: undefined,
    next_shift_id: undefined,
});

const updateformId = "shift-update-form";
const updateForm = useForm({
    shift_id: undefined,
    ...getTranslatableData(
        { name: "", },
        props.shifts,
    ),
    started_at: undefined,
    ended_at: undefined,
    next_shift_id: undefined,
});

const ScheduleFormId = "shift-Schedule-create-form";
const ScheduleForm = useForm({
    shift_id: undefined,
    shift_time_id: undefined,
    day_of_week: undefined,
});
const EditScheduleFormId = "shift-Schedule-create-form";
const EditScheduleForm = useForm({
    shift_id: undefined,
    shift_time_id: undefined,
    old_shift_time_id: undefined,
    day_of_week: undefined,
});
const EmployeeFormId = "employee-shift-create-form";
const EmployeeForm = useForm({
    shift_id: undefined,
    employee_id: []
});



const EditShift = (shift): void => {
    showUpdateForm.value = true;

    updateForm.shift_id = shift.id;
    for (const locale of shift.translations) {

        updateForm[locale.locale].name = locale.name;

    }
    updateForm.started_at = shift.started_at;
    updateForm.ended_at = shift.ended_at;
    updateForm.next_shift_id = shift.next_shift_id;

};
const AssignToUsers = (shift): void => {
    ShowEmployeeForm.value = true;

    EmployeeForm.shift_id = shift.id;

};
const OpenScheduleForm = (shift, day): void => {
    showScheduleForm.value = true;

    ScheduleForm.shift_id = shift.id;
    ScheduleForm.day_of_week = day;

};
const OpenEditScheduleForm = (shift, shift_time, day): void => {
    showEditScheduleForm.value = true;
    EditScheduleForm.shift_id = shift.id;
    EditScheduleForm.old_shift_time_id = shift_time.id;
    EditScheduleForm.day_of_week = day;
};
const RestDay = (shift, shift_time, day): void => {
    axios.post(route('shifts.MarkasRestDay'), {
        shift_id: shift.id,
        shift_time_id: shift_time.id,
        day_of_week: day
    });

};

const showCreateForm = ref(false);
const submit = () => {
    form.post(route("shifts.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`shifts/${updateForm.shift_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};

const showScheduleForm = ref(false);

const schedule = () => {
    ScheduleForm.post(route('shifts.assignSchedule')), {
        onSuccess: () => {
            showScheduleForm.value = false;
            ScheduleForm.reset();
        },

    }
};
const showEditScheduleForm = ref(false);
const EditSchedule = () => {
    EditScheduleForm.post(route('shifts.EditSchedule')), {
        onSuccess: () => {
            showEditScheduleForm.value = false;
            EditScheduleForm.reset();
        },

    }
};

const ShowEmployeeForm = ref(false);
const AssignShiftToEmployee = () => {
    EmployeeForm.post(route('shifts.assign_to_employees')), {
        onSuccess: () => {
            ShowEmployeeForm.value = false;
            EmployeeForm.reset();
        },

    }
};

const removeShift = (shift_id: number) => {
    axios
        .delete(route(`shifts.delete`, { shift: shift_id }), {})
        .then((response) => { })
        .catch((error) => {
            console.error("Error Removing Shift:", error.response.data);
        });
};
const getHeaderForDay = (dayIndex: number): number => {
    return dayValues[dayIndex + 1]; // Assuming the headers array is 1-indexed
};
const headers = [
    "اسم الدوام",
    "السبت",
    "الأحد",
    "الاثنين",
    "الثلاثاء",
    "الأربعاء",
    "الخميس",
    "الجمعة",
    "تاريخ الانشاء",
];

const dayValues = [
    0, 1, 2, 3, 4, 5, 6
];

</script>

<template>
    <div>
        <Header title="جدول الدوام" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="إضافة دوام">
                    <template #trigger>
                        <Button variant="success" label="إضافة دوام" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>
                                <formField label="بداية العمل" :error="form.errors.started_at">
                                    <DateInput v-model="form.started_at" />
                                </formField>
                                <formField label=" نهاية العمل" :error="form.errors.ended_at">
                                    <DateInput v-model="form.ended_at" />
                                </formField>

                                <FormField label="الدوام التالي" :error="form.errors.next_shift_id">

                                    <Combobox v-model="form.next_shift_id" :multiple="false" :options="all_shifts"
                                        option-label="name" option-value="id" />

                                </FormField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="تعديل دوام">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">
                                <FormField label="" :error="updateForm.errors.shift_id">
                                    <Input v-model="updateForm.shift_id" type="hidden" />
                                </FormField>
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
									<FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
										<Input v-model="updateForm[locale].name" />
									</FormField>
								</template>
                                <formField label="بداية العمل" :error="updateForm.errors.started_at">
                                    <DateInput v-model="updateForm.started_at" type="time" />
                                </formField>
                                <formField label=" نهاية العمل" :error="updateForm.errors.ended_at">
                                    <DateInput v-model="updateForm.ended_at" type="time" />
                                </formField>

                                <FormField label="اختيار الدوام القادم" :error="updateForm.errors.next_shift_id">
                                    <Combobox v-model="updateForm.next_shift_id" :multiple="false" :options="all_shifts"
                                        option-label="name" option-value="id" />
                                </FormField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showScheduleForm" title="تعديل دوام">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="ScheduleFormId" @submit="schedule">
                                <FormField label="" :error="ScheduleForm.errors.shift_id">
                                    <Input v-model="ScheduleForm.shift_id" type="hidden" />
                                </FormField>
                                <FormField label="" :error="ScheduleForm.errors.day_of_week">
                                    <Input v-model="ScheduleForm.day_of_week" type="hidden" />
                                </FormField>

                                <FormField label="الدوامات" :error="ScheduleForm.errors.shift_time_id">
                                    <!--peter change this so the label can have start_time and end_time -->
                                    <Combobox v-model="ScheduleForm.shift_time_id" :multiple="false" :options="times"
                                        option-label="start_time" option-value="id" />
                                </FormField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift-Schedule-create-form" type="submit" label="حفظ"
                            :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showEditScheduleForm" title="تعديل دوام">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="EditScheduleFormId" @submit="EditSchedule">
                                <FormField label="" :error="EditScheduleForm.errors.shift_id">
                                    <Input v-model="EditScheduleForm.shift_id" type="hidden" />
                                </FormField>
                                <FormField label="" :error="EditScheduleForm.errors.old_shift_time_id">
                                    <Input v-model="EditScheduleForm.old_shift_time_id" type="hidden" />
                                </FormField>
                                <FormField label="" :error="EditScheduleForm.errors.day_of_week">
                                    <Input v-model="EditScheduleForm.day_of_week" type="hidden" />
                                </FormField>

                                <FormField label="الدوامات" :error="EditScheduleForm.errors.shift_time_id">
                                    <!--peter change this so the label can have start_time and end_time -->
                                    <Combobox v-model="EditScheduleForm.shift_time_id" :multiple="false"
                                        :options="times" option-label="start_time" option-value="id" />
                                </FormField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift-Schedule-create-form" type="submit" label="حفظ"
                            :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="ShowEmployeeForm" title="تعديل دوام">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="EmployeeFormId" @submit="AssignShiftToEmployee">
                                <FormField label="" :error="EmployeeForm.errors.shift_id">
                                    <Input v-model="EmployeeForm.shift_id" type="hidden" />
                                </FormField>


                                <FormField label="الموظفين" :error="EmployeeForm.errors.employee_id">
                                    <!--peter change this so the label can have start_time and end_time -->
                                    <Combobox v-model="EmployeeForm.employee_id" :multiple="true" :options="employees"
                                        option-label="ar_username" option-value="id" />
                                </FormField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="employee-shift-create-form" type="submit" label="حفظ"
                            :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="shifts">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>

                        <!--  <div >
                            <TableCell v-for="shift in row?.shift_times" :key="shift.id">
                                <div  v-for="(day, index) in dayValues" :key="index">
                                    <div v-if="index === shift?.pivot.day_of_week">
                                        {{ shift?.start_time }} - {{ shift?.end_time }}
                                    </div>
                                    <div v-else>
                                        <button @click="OpenScheduleForm(row)">اضافة دوام</button>
                                        <button @click="OpenEditScheduleForm(row,shift,shift?.pivot.day_of_week)">تعديل دوام</button>
                                    </div>
                                </div>
                            </TableCell>
                        </div> -->

                        <TableCell v-for="(shift, index) in row?.shift_times" :key="shift.id">
                            {{ shift?.start_time }} - {{ shift?.end_time }}
                            <button @click="OpenScheduleForm(row, getHeaderForDay(index))">اضافة دوام</button>
                            <button @click="OpenEditScheduleForm(row, shift, shift?.pivot.day_of_week)">تعديل
                                دوام</button>
                            <button @click="RestDay(row, shift, shift?.pivot.day_of_week)">تغير الي يوم راحة</button>

                        </TableCell>
                        <button @click="OpenScheduleForm(row, 1)">اضافة دوام</button>







                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="EditShift(row)" />
                                    <DropdownMenuItem label="تعيين الي موظف" icon="i-tabler-pencil"
                                        @click="AssignToUsers(row)" />

                                    <AlertDialog title="حذف الدوام" description="هل أنت متأكد من حذف هذا الدوام؟"
                                        @confirm="() => removeShift(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الدوام" icon="i-tabler-trash"
                                            :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<style></style>
