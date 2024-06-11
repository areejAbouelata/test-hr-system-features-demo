<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    leaves?: PaginatedData<{
        id: number | string;
        number_of_days: number | string;
        deduct_type: string;
        half_day: boolean;
        balance_type: string;
        year_end_options: string;
        transfer_some_amount: number;
        created_at: string;
        created_by: string;
        updated_by: string;

    }>;


}>();
const breadcrumbs: BreadCrumbs = [
    {
        title: "Annual Leaves",
        href: route("annual_leaves.index"),
    },
];

const formId = "leave-create-form";
const form = useForm({

    number_of_days: undefined,
    deduct_type: undefined,
    half_day: undefined,
    balance_type: undefined,
    year_end_options: undefined,
    transfer_some_amount: undefined
});

const updateformId = "leave-update-form";
const updateForm = useForm({
    leave_id: undefined,
    number_of_days: undefined,
    deduct_type: undefined,
    half_day: undefined,
    balance_type: undefined,
    year_end_options: undefined,
    transfer_some_amount: undefined
});

const EditShift = (leave): void => {
    showUpdateForm.value = true;

    updateForm.leave_id = leave.id;
    updateForm.number_of_days = leave.number_of_days;
    updateForm.deduct_type = leave.deduct_type,
        updateForm.half_day = leave.half_day,
        updateForm.balance_type = leave.balance_type,
        updateForm.year_end_options = leave.year_end_options,
        updateForm.transfer_some_amount = leave.transfer_some_amount

};






const showCreateForm = ref(false);
const submit = () => {
    form.post(route("annual_leaves.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`annual_leaves/${updateForm.leave_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};


const deleteLeave = async (leave_id: number) => {
    updateForm.delete(`annual_leaves/${leave_id}`)
};
const softdeleteLeave = async (leave_id: number) => {
    updateForm.delete(`annual_leaves/${leave_id}/softRestore`)
};


const headers = [
    "number_of_days",
    "deduct_type",
    "half_day",
    "balance_type",
    "year_end_options",
    "transfer_some_amount",
    "last_action",
    "actions",

];


</script>

<template>
    <div>
        <Header title="Annual Leaves DataTable" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="create">
                    <template #trigger>
                        <Button variant="success" label="create" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">
                                <formField label="number_of_days" :error="form.errors.number_of_days">
                                    <Input v-model="form.number_of_days" type="number" />
                                </formField>
                                <formField label="deduct type" :error="form.errors.deduct_type">
                                    <Select v-model="form.deduct_type" :options="['work_days', 'all_days']" />
                                </formField>
                                <formField label="half_day" :error="form.errors.half_day">
                                    <input v-model="form.half_day" type="checkbox" />
                                </formField>

                                <formField label="balance_type" :error="form.errors.balance_type">
                                    <Select v-model="form.balance_type"
                                        :options="['all_available_balance', 'cumulative_balance']" />
                                </formField>
                                <formField label="year_end_options" :error="form.errors.year_end_options">
                                    <Select v-model="form.year_end_options"
                                        :options="['transfer_none', 'transfer_all', 'transfer_some']" />
                                </formField>
                                <formField label="transfer_some_amount" :error="form.errors.transfer_some_amount"
                                    v-if="form.year_end_options === 'transfer_some'">
                                    <Input v-model="form.transfer_some_amount" />
                                </formField>


                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="leave-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="Edit Leave">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">

                                <input v-model="updateForm.leave_id" type="hidden" />

                                <formField label="number_of_days" :error="updateForm.errors.number_of_days">
                                    <Input v-model="updateForm.number_of_days" type="number" />
                                </formField>
                                <formField label="deduct type" :error="updateForm.errors.deduct_type">
                                    <Select v-model="updateForm.deduct_type" :options="['work_days', 'all_days']" />
                                </formField>
                                <formField label="half_day" :error="updateForm.errors.half_day">
                                    <input v-model="updateForm.half_day" type="checkbox" />
                                </formField>

                                <formField label="balance_type" :error="updateForm.errors.balance_type">
                                    <Select v-model="updateForm.balance_type"
                                        :options="['all_available_balance', 'cumulative_balance']" />
                                </formField>
                                <formField label="year_end_options" :error="updateForm.errors.year_end_options">
                                    <Select v-model="updateForm.year_end_options"
                                        :options="['transfer_none', 'transfer_all', 'transfer_some']" />
                                </formField>
                                <formField label="transfer_some_amount" :error="updateForm.errors.transfer_some_amount"
                                    v-if="updateForm.year_end_options === 'transfer_some'">
                                    <Input v-model="updateForm.transfer_some_amount" />
                                </formField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="leave-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>


            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="leaves">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.number_of_days }}
                        </TableCell>
                        <TableCell>
                            {{ row?.deduct_type }}
                        </TableCell>
                        <TableCell>
                            {{ row?.half_day }}
                        </TableCell>
                        <TableCell>
                            {{ row?.balance_type }}
                        </TableCell>
                        <TableCell>
                            {{ row?.year_end_options }}
                        </TableCell>
                        <TableCell>
                            {{ row?.transfer_some_amount }}
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


                                    <AlertDialog title="Permanant delete Leave"
                                        description="Are you sure you want to delete this Leave?"
                                        @confirm="() => deleteLeave(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="Permanant Delete Leave"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="" description="Are you sure you want to delete this Leave?"
                                        @confirm="() => softdeleteLeave(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="delete Leave" icon="i-tabler-trash"
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
