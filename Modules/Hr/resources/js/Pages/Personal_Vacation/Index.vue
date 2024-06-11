<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    vacations?: PaginatedData<{
        id: number | string;
        name: string;
        type: string;
        number_of_days: number | string;
        vacation_status: string;
        status: string;
        created_at: string;
        created_by: string;
        updated_by: string;
    }>;
}>();

const breadcrumbs: BreadCrumbs = [
    {
        title: "personal vacations",
        href: route("personal_vacation.index"),
    },
];

const formId = "vacation-create-form";
const form = useForm({
    name: undefined,
    type: undefined,
    number_of_days: undefined,
    vacation_status: undefined,
    status: undefined,
});

const updateformId = "vacation-update-form";
const updateForm = useForm({
    vacation_id: undefined,
    name: undefined,
    type: undefined,
    number_of_days: undefined,
    vacation_status: undefined,
    status: undefined,
});

const editVacation = (vacation): void => {
    showUpdateForm.value = true;

    updateForm.vacation_id = vacation.id;
    updateForm.name = vacation.name;
    updateForm.type = vacation.type;
    updateForm.number_of_days = vacation.number_of_days;
    updateForm.vacation_status = vacation.vacation_status;
    updateForm.status = vacation.status;
};

const showCreateForm = ref(false);
const submit = () => {
    form.post(route("personal_vacation.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`personal_vacations/${updateForm.vacation_id}`, {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    });
};

const deleteVacation = async (vacation_id: number) => {
    updateForm.delete(`personal_vacations/${vacation_id}`);
};

const softDeleteVacation = async (vacation_id: number) => {
    updateForm.delete(`personal_vacations/${vacation_id}/softRestore`);
};

const headers = [
    "name",
    "type",
    "status",
    "number_of_days",
    'last_action',
    'vacation_status',
    "actions",
];
</script>

<template>
    <div>
        <Header title="جدول الدوام" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="إضافة إجازة">
                    <template #trigger>
                        <Button variant="success" label="إضافة إجازة" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">
                                <formField label="الاسم" :error="form.errors.name">
                                    <Input v-model="form.name" />
                                </formField>
                                <formField label="نوع الإجازة" :error="form.errors.type">
                                   <combobox v-model="form.type" :options="['paid', 'unpaid']" />
                                </formField>
                                <formField label="عدد الأيام" :error="form.errors.number_of_days">
                                    <Input v-model="form.number_of_days" type="number" />
                                </formField>
                                <formField label="حالة الإجازة" :error="form.errors.vacation_status">
                                   <combobox v-model="form.vacation_status" :options="['active', 'inactive']" />
                                </formField>
                                <formField label="الحالة " :error="form.errors.status">
                                   <combobox v-model="form.status" :options="['active', 'inactive']" />
                                </formField>

                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="vacation-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="تعديل إجازة">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">
                                <formField label="الاسم" :error="updateForm.errors.name">
                                    <Input v-model="updateForm.name" />
                                </formField>
                                <formField label="نوع الإجازة" :error="updateForm.errors.type">
                                    <Input v-model="updateForm.type" />
                                </formField>
                                <formField label="عدد الأيام" :error="updateForm.errors.number_of_days">
                                    <Input v-model="updateForm.number_of_days" type="number" />
                                </formField>
                                <formField label="حالة الإجازة" :error="updateForm.errors.vacation_status">
                                   <combobox v-model="updateForm.vacation_status" :options="['active', 'inactive']" />
                                </formField>
                                <formField label="الحالة " :error="updateForm.errors.status">
                                   <combobox v-model="updateForm.status" :options="['active', 'inactive']" />
                                </formField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="vacation-update-form" type="submit" label="حفظ"
                            :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="vacations">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>
                        <TableCell>
                            {{ row?.type }}
                        </TableCell>
                        <TableCell>
                            {{ row?.status }}
                        </TableCell>
                        <TableCell>
                            {{ row?.number_of_days }}
                        </TableCell>
                        <TableCell>
                            {{ row?.updated_by || row?.created_by }}
                        </TableCell>
                        <TableCell>
                            {{ row?.vacation_status }}
                        </TableCell>
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="editVacation(row)" />
                                    <AlertDialog title="حذف الإجازة النهائي"
                                        description="هل أنت متأكد من حذف هذه الإجازة؟"
                                        @confirm="() => deleteVacation(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الإجازة النهائي"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="حذف الإجازة" description="هل أنت متأكد من حذف هذه الإجازة؟"
                                        @confirm="() => softDeleteVacation(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الإجازة" icon="i-tabler-trash"
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
