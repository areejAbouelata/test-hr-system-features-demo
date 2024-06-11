<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    shifts?: PaginatedData<{
        id: number | string;
        name: string;
        flexiable: boolean;
        hours_per_day: number | string;
        start_time: string;
        end_time: string;
        created_at: string;
        created_by: string;
        updated_by: string;
      
    }>;
   

}>();
const breadcrumbs: BreadCrumbs = [
    {
        title: "الدوامات",
        href: route("shifts.index"),
    },
];

const formId = "shift-create-form";
const form = useForm({
    name: undefined,
    start_time: undefined,
    end_time: undefined,
    flexible: undefined,
    hours_per_day: undefined,
});

const updateformId = "shift-update-form";
const updateForm = useForm({
    shift_id: undefined,
    name: undefined,
    start_time: undefined,
    end_time: undefined,
    flexible: undefined,
    hours_per_day: undefined,
});

const EditShift = (shift): void => {
    showUpdateForm.value = true;

    updateForm.shift_id = shift.id;
    updateForm.name = shift.name;

    updateForm.start_time = shift.start_time;
    updateForm.end_time = shift.end_time;
    updateForm.flexible = shift.flexible;
    updateForm.hours_per_day = shift.hours_per_day;

};






const showCreateForm = ref(false);
const submit = () => {
    form.post(route("open_shifts.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`open_shifts/${updateForm.shift_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};


const deleteShift = async (shift_id: number) => {
    updateForm.delete(`open_shifts/${shift_id}`)
};
const softdeleteShift= async (shift_id: number) => {
    updateForm.delete(`open_shifts/${shift_id}/softRestore`)
};


const headers = [
    "اسم الدوام",
    "وقت التسجيل",
    "مدة العمل",

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
                                <formField label="الاسم" :error="form.errors.name">
                                    <Input v-model="form.name" />
                                </formField>
                                <formField label="عدد ساعات العمل" :error="form.errors.hours_per_day">
                                    <Input v-model="form.hours_per_day" type="number"/>
                                </formField>
                                <formField label="المرونة" :error="form.errors.flexible">
                                    <input v-model="form.flexible" type="checkbox"/>
                                </formField>

                                <formField label="بداية العمل" :error="form.errors.start_time">
                                    <Input type="time" v-model="form.start_time" />
                                </formField>
                                <formField label=" نهاية العمل" :error="form.errors.end_time">
                                    <Input type="time" v-model="form.end_time" />
                                </formField>

                               
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
                                <formField label="الاسم" :error="updateForm.errors.shift_id">
                                    <Input v-model="updateForm.shift_id"  type="hidden"/>
                                </formField>
                                <formField label="الاسم" :error="updateForm.errors.name">
                                    <Input v-model="updateForm.name" />
                                </formField>
                                <formField label="الاسم" :error="updateForm.errors.hours_per_day">
                                    <Input v-model="updateForm.hours_per_day" type="number"/>
                                </formField>
                                <!-- Peter on Toggle this should show start_time and end_time inputs-->                                <formField label="المرونة" :error="updateForm.errors.flexible">
                                    <Input type="checkbox" v-model="updateForm.flexible" />
                                </formField>

                                <formField label="بداية العمل" :error="updateForm.errors.start_time">
                                    <Input type="time" v-model="updateForm.start_time" />
                                </formField>
                                <formField label=" نهاية العمل" :error="updateForm.errors.end_time">
                                    <Input type="time" v-model="updateForm.end_time" />
                                </formField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="shift-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
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
                        <TableCell>
                           Anytime {{ row?.start_time }} {{ row?.end_time }}
                        </TableCell>
                        <TableCell>
                            {{ row?.hours_per_day }}
                        </TableCell>

                        <TableCell>
                            <div v-if="row?.updated_by">
                                {{ row?.updated_by }}

                            </div>
                            <div v-else>
                                {{ row?.created_by }}
                            </div>
                        </TableCell>
                        





                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="EditShift(row)" />
                                   

                                    <AlertDialog title="حذف الدوام المفتوح النهائي"
                                        description="هل أنت متأكد من حذف هذه الدوام المفتوح؟"
                                        @confirm="() => deleteShift(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label=" حذف الدوام المفتوح النهائي"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="حذف الدوام المفتوح" description="هل أنت متأكد من حذف هذه الدوام المفتوح؟"
                                        @confirm="() => softdeleteShift(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الدوام المفتوح" icon="i-tabler-trash"
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
