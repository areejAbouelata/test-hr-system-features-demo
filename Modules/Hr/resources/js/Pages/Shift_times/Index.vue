<script setup lang="ts">
const props = defineProps<{
    times?: PaginatedData<{
        id: number | string;
        name: string;
        start_time: string;
        end_time: string;
        next_day: boolean;
        work_hour_duration: string;
        flexibility: boolean;
        minutes_compensation: number;
        before_sign_in: number;
        after_sign_in: number;
        before_sign_out: number;
        after_sign_out: number;
        allowed_delay_for_sign_in: number;
        allowed_delay_for_sign_out: number;
        overtime: boolean;
        max_overtime_duration: number;
        updated_at: string;
        created_at: string;
        created_by: string;
        updated_by: string;
        translations: Record<string, { name: string }>;

    }>;






}>();
const breadcrumbs: BreadCrumbs = [
    {
        title: "اوقات العمل",
        href: route("shift_times.index"),
    },
];

const formId = "shift_time-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.times,
    ),
    start_time: undefined,
    end_time: undefined,
    next_day: false,
    work_hour_duration: undefined,
    flexibility: false,
    minutes_compensation: undefined,
    before_sign_in: undefined,
    after_sign_in: undefined,
    before_sign_out: undefined,
    after_sign_out: undefined,
    allowed_delay_for_sign_in: undefined,
    allowed_delay_for_sign_out: undefined,
    overtime: false,
    max_overtime_duration: undefined
});

const updateformId = "shift_time-update-form";
const updateForm = useForm({
    shift_time_id: undefined,
    ...getTranslatableData(
        { name: "", },
        props.times,
    ),
    start_time: undefined,
    end_time: undefined,
    next_day: false,
    work_hour_duration: undefined,
    flexibility: false,
    minutes_compensation: undefined,
    before_sign_in: undefined,
    after_sign_in: undefined,
    before_sign_out: undefined,
    after_sign_out: undefined,
    allowed_delay_for_sign_in: undefined,
    allowed_delay_for_sign_out: undefined,
    overtime: false,
    max_overtime_duration: undefined
});



const AssignUser = (shift_time): void => {
    showUpdateForm.value = true;

    updateForm.shift_time_id = shift_time.id;
    for (const locale of shift_time.translations) {

        updateForm[locale.locale].name = locale.name;

    }

    updateForm.start_time = shift_time.start_time;
    updateForm.end_time = shift_time.end_time;
    updateForm.next_day = shift_time.next_day;
    updateForm.work_hour_duration = shift_time.work_hour_duration;
    updateForm.flexibility = shift_time.flexibility;
    updateForm.minutes_compensation = shift_time.minutes_compensation;
    updateForm.before_sign_in = shift_time.before_sign_in;
    updateForm.after_sign_in = shift_time.after_sign_in;
    updateForm.before_sign_out = shift_time.before_sign_out;
    updateForm.after_sign_out = shift_time.after_sign_out;
    updateForm.allowed_delay_for_sign_in = shift_time.allowed_delay_for_sign_in;
    updateForm.allowed_delay_for_sign_out = shift_time.allowed_delay_for_sign_out;
    updateForm.overtime = shift_time.overtime;
    updateForm.max_overtime_duration = shift_time.max_overtime_duration;


};




const showCreateForm = ref(false);
const submit = () => {
    form.post(route("shift_times.store"), {
        onSuccess: () => {
            form.reset();
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`shift_times/${updateForm.shift_time_id}`), {
        onSuccess: () => {
            showUpdateForm.value = false;
            updateForm.reset();
        },
    };
};


const deleteTime = async (unit_id: number) => {
    updateForm.delete(`shift_times/${unit_id}`)
};
const softdeleteTime = async (unit_id: number) => {
    updateForm.delete(`shift_times/${unit_id}`)
};


watch([() => form.start_time, () => form.end_time], ([newStartTime, newEndTime], [oldStartTime, oldEndTime]) => {
    if (newStartTime && newEndTime) {
        // Initialize both dates on the same arbitrary day
        const startTime = new Date(`01/01/2022 ${newStartTime}`);
        const endTime = new Date(`01/01/2022 ${newEndTime}`);

        // Check if end_time is technically the next day
        if (endTime < startTime) {
            // Adjust endTime by adding one day
            endTime.setDate(endTime.getDate() + 1);
            form.next_day = true;
        } else {
            form.next_day = false;
        }

        // Calculate time difference
        const timeDiff = endTime - startTime; // This will be positive or zero
        let hourDiff = timeDiff / (1000 * 3600); // Convert milliseconds to hours

        // Round the hour difference to one decimal place
        form.work_hour_duration = (Math.round(hourDiff * 10) / 10).toFixed(1);
    }
});


const headers = [
    'الاسم',
    'الوقت',
    'اليوم التالي',
    'مدة ساعات العمل',
    'المرونة',
    'تعويض الدقائق',
    'قبل توقيع الدخول',
    'بعد توقيع الدخول',
    'قبل توقيع الخروج',
    'بعد توقيع الخروج',
    'المسموح بالتأخير عن توقيع الدخول',
    'المسموح بالتأخير عن توقيع الخروج',
    'الوقت الإضافي',
    'الحد الأقصى لمدة الوقت الإضافي',
    'اخر تعديل',
    'الاجرائات',
];

</script>
<template>
    <div>

        <Header title="جدول اوقات العمل" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="إضافة وحدة">
                    <template #trigger>
                        <Button variant="success" label="إضافة وحدة" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">

                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="بداية الوقت" :error="form.errors.start_time">
                                    <Input v-model="form.start_time" type="time" />
                                </formField>
                                <formField label="نهاية" :error="form.errors.end_time">
                                    <Input v-model="form.end_time" type="time" />
                                </formField>
                                <formField label="الوقت التالي" :error="form.errors.next_day">
                                    <Checkbox v-model="form.next_day" :checked="form.next_day" />
                                </formField>

                                <formField label="مدة ساعات العمل" :error="form.errors.work_hour_duration">
                                    <Input v-model="form.work_hour_duration" readonly />
                                </formField>

                                <formField label="المرونة" :error="form.errors.flexibility">
                                    <Checkbox v-model="form.flexibility" />
                                </formField>

                                <formField label="تعويض الدقائق" :error="form.errors.minutes_compensation">
                                    <Input v-model="form.minutes_compensation" type="number" />
                                </formField>

                                <formField label="قبل توقيع الدخول" :error="form.errors.before_sign_in">
                                    <Input v-model="form.before_sign_in" type="number" />
                                </formField>

                                <formField label="بعد توقيع الدخول" :error="form.errors.after_sign_in">
                                    <Input v-model="form.after_sign_in" type="number" />
                                </formField>
                                <formField label="قبل توقيع الخروج" :error="form.errors.before_sign_out">
                                    <Input v-model="form.before_sign_out" type="number" />
                                </formField>

                                <formField label="بعد توقيع الخروج" :error="form.errors.after_sign_out">
                                    <Input v-model="form.after_sign_out" type="number" />
                                </formField>

                                <formField label="المسموح بالتأخير عن توقيع الدخول"
                                    :error="form.errors.allowed_delay_for_sign_in">
                                    <Input v-model="form.allowed_delay_for_sign_in" type="number" />
                                </formField>

                                <formField label="المسموح بالتأخير عن توقيع الخروج"
                                    :error="form.errors.allowed_delay_for_sign_out">
                                    <Input v-model="form.allowed_delay_for_sign_out" type="number" />
                                </formField>

                                <formField label="الوقت الإضافي" :error="form.errors.overtime">
                                    <Checkbox v-model="form.overtime" />
                                </formField>

                                <formField label="الحد الأقصى لمدة الوقت الإضافي"
                                    :error="form.errors.max_overtime_duration">
                                    <Input v-model="form.max_overtime_duration" type="number" />
                                </formField>



                            </Form>



                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift_time-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="تعديل وحدة">

                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">
                                <FormField label="" :error="updateForm.errors.shift_time_id">
                                    <Input v-model="updateForm.shift_time_id" type="hidden" />
                                </FormField>

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
                                        <Input v-model="updateForm[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="بداية الوقت" :error="updateForm.errors.start_time">
                                    <Input v-model="updateForm.start_time" type="time" />
                                </formField>
                                <formField label="نهاية" :error="updateForm.errors.end_time">
                                    <Input v-model="updateForm.end_time" type="time" />
                                </formField>
                                <formField label="الوقت التالي" :error="updateForm.errors.next_day">
                                    <Checkbox v-model="updateForm.next_day" />
                                </formField>

                                <formField label="مدة ساعات العمل" :error="updateForm.errors.work_hour_duration">
                                    <Input v-model="updateForm.work_hour_duration" readonly />
                                </formField>

                                <formField label="المرونة" :error="updateForm.errors.flexibility">
                                    <Checkbox v-model="updateForm.flexibility" />
                                </formField>

                                <formField label="تعويض الدقائق" :error="updateForm.errors.minutes_compensation">
                                    <Input v-model="updateForm.minutes_compensation" type="number" />
                                </formField>

                                <formField label="قبل توقيع الدخول" :error="updateForm.errors.before_sign_in">
                                    <Input v-model="updateForm.before_sign_in" type="number" />
                                </formField>

                                <formField label="بعد توقيع الدخول" :error="updateForm.errors.after_sign_in">
                                    <Input v-model="updateForm.after_sign_in" type="number" />
                                </formField>
                                <formField label="قبل توقيع الخروج" :error="updateForm.errors.before_sign_out">
                                    <Input v-model="updateForm.before_sign_out" type="number" />
                                </formField>

                                <formField label="بعد توقيع الخروج" :error="updateForm.errors.after_sign_out">
                                    <Input v-model="updateForm.after_sign_out" type="number" />
                                </formField>

                                <formField label="المسموح بالتأخير عن توقيع الدخول"
                                    :error="updateForm.errors.allowed_delay_for_sign_in">
                                    <Input v-model="updateForm.allowed_delay_for_sign_in" type="number" />
                                </formField>

                                <formField label="المسموح بالتأخير عن توقيع الخروج"
                                    :error="updateForm.errors.allowed_delay_for_sign_out">
                                    <Input v-model="updateForm.allowed_delay_for_sign_out" type="number" />
                                </formField>

                                <formField label="الوقت الإضافي" :error="updateForm.errors.overtime">
                                    <Checkbox v-model="updateForm.overtime" />
                                </formField>

                                <formField label="الحد الأقصى لمدة الوقت الإضافي"
                                    :error="updateForm.errors.max_overtime_duration">
                                    <Input v-model="updateForm.max_overtime_duration" type="number" />
                                </formField>



                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift_time-update-form" type="submit" label="حفظ"
                            :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>

            </template>
        </Header>

        <div class="page-content">
            <div class="panel">

                <DataTable :headers :data="times">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>
                        <TableCell>
                            {{ row?.start_time }} - {{ row?.end_time }}
                        </TableCell>
                        <TableCell>
                            <div v-if="row?.next_day === true">
                                <Badge label="اليوم التالي" variant="success" />
                            </div>

                        </TableCell>
                        <TableCell>
                            {{ row?.work_hour_duration }}
                        </TableCell>
                        <TableCell>
                            {{ row?.flexibility }}
                        </TableCell>
                        <TableCell>
                            {{ row?.minutes_compensation }}
                        </TableCell>
                        <TableCell>
                            {{ row?.before_sign_in }}
                        </TableCell>
                        <TableCell>
                            {{ row?.after_sign_in }}
                        </TableCell>
                        <TableCell>
                            {{ row?.before_sign_out }}
                        </TableCell>
                        <TableCell>
                            {{ row?.after_sign_out }}
                        </TableCell>
                        <TableCell>
                            {{ row?.allowed_delay_for_sign_in }}
                        </TableCell>
                        <TableCell>
                            {{ row?.allowed_delay_for_sign_out }}
                        </TableCell>
                        <TableCell>
                            {{ row?.overtime }}
                        </TableCell>
                        <TableCell>
                            {{ row?.max_overtime_duration }}
                        </TableCell>

                        <TableCell>
                            <div v-if="row?.created_by">
                                {{ row?.created_by }}

                            </div>
                            <div v-else-if="row?.updated_by">
                                {{ row?.updated_by }}

                            </div>

                        </TableCell>

                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="AssignUser(row)" />
                                    <AlertDialog title="حذف التوقيت النهائي"
                                        description="هل أنت متأكد من حذف هذه التوقيت؟"
                                        @confirm="() => deleteTime(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label=" حذف التوقيت النهائي"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="حذف التوقيت" description="هل أنت متأكد من حذف هذه التوقيت؟"
                                        @confirm="() => softdeleteTime(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف التوقيت" icon="i-tabler-trash"
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
