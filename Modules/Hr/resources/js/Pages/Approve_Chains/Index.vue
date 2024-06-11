<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    chains?: PaginatedData<{
        id: number | string;
        name: string;
        description: string;
        employee_id: string;
        created_at: string;
        created_by: string;
        updated_by: string;
        staff_count: number | string;
        employees_count: number | string;
        translations: Record<string, { name: string }>;


    }>;
    employees?: {
        name: string;
        id: number | string;
    }


}>();
const locales = ["ar", "en"];

const breadcrumbs: BreadCrumbs = [
    {
        title: "Approve Chains",
        href: route("approve_chains.index"),
    },
];

const formId = "chain-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.chains,
    ),
    description: undefined,
    employee_id: [],
    staff_order: []
});

const updateformId = "chain-update-form";
const updateForm = useForm({
    chain_id: undefined,
    ...getTranslatableData(
        { name: "", },
        props.chains,
    ),
    description: undefined,
    employee_id: [],
    staff_order: [],

});

const EditTask = (chain): void => {
    showUpdateForm.value = true;

    updateForm.chain_id = chain.id;
    for (const locale of chain.translations) {

        updateForm[locale.locale].name = locale.name;

    }

    updateForm.description = chain.description;
    updateForm.employee_id = chain.employee_id;


};






const showCreateForm = ref(false);
const submit = () => {
    form.post(route("approve_chains.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`approve_chains/${updateForm.chain_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};

const addStaffIdField = () => {
    // Push a new empty value to the staff_id array
    form.staff_order.push('');
};
const addEditStaffIdField = () => {
    // Push a new empty value to the staff_order array
    updateForm.staff_order.push('');
};
const deleteTask = async (chain_id: number) => {
    updateForm.delete(`approve_chains/${chain_id}`)
};
const softdeleteTask = async (chain_id: number) => {
    updateForm.delete(`approve_chains/${chain_id}/softRestore`)
};


const headers = [
    "name",
    "number of employees",
    "number of staff",
    "last_action",
    "actions",

];


</script>

<template>
    <div>
        <Header title="Approve Chains DataTable" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="create">
                    <template #trigger>
                        <Button variant="success" label="create" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="description" :error="form.errors.description">
                                    <Input v-model="form.description" />
                                </formField>
                                <formField label="employee" :error="form.errors.description">
                                    <Select v-model="form.employee_id" :options="employees" option-value="id"
                                        option-label="ar_username" :multiple="true" />
                                </formField>
                                <Button @click="addStaffIdField" variant="primary" label="Add Manager" />

                                <template v-for="(staffId, index) in form.staff_order" :key="index">
                                    <formField :label="`Manager ${index + 1}`" :error="form.errors.staff_order">
                                        <Select v-model="form.staff_order[index]"
                                            :options="['branch_manager', 'unit_manager', 'department_manager', 'parent_department_manager']" />
                                    </formField>
                                </template>


                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="chain-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="Edit Task">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">

                                <input v-model="updateForm.chain_id" type="hidden" />

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
                                        <Input v-model="updateForm[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="description" :error="updateForm.errors.description">
                                    <Input v-model="updateForm.description" />
                                </formField>
                                <formField label="employee" :error="updateForm.errors.description">
                                    <Select v-model="updateForm.employee_id" :options="employees" option-value="id"
                                        option-label="ar_username" />
                                </formField>
                                <Button @click="addEditStaffIdField" variant="primary" label="Add Manager" />

                                <template v-for="(staffId, index) in form.staff_order" :key="index">
                                    <formField :label="`Manager ${index + 1}`">
                                        <Select v-model="updateForm.staff_order[index]"
                                            :options="['branch_manager', 'unit_manager', 'department_manager', 'parent_department_manager']" />
                                    </formField>
                                </template>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="chain-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>


            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="chains">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>
                        <TableCell>
                            {{ row?.employees_count }}<!--  {{ $page.props.current_user_permissions }} -->
                        </TableCell>
                        <TableCell>
                            {{ row?.staff_count }} {{ $page.props.current_user_roles }}
                        </TableCell>

                        <TableCell>

                            {{ row?.updated_by  || row?.created_by }}
                        </TableCell>

                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="EditTask(row)" />


                                    <AlertDialog title="Permanant delete Task"
                                        description="Are you sure you want to delete this Task?"
                                        @confirm="() => deleteTask(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="Permanant Delete Task"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="" description="Are you sure you want to delete this Task?"
                                        @confirm="() => softdeleteTask(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="delete Task" icon="i-tabler-trash"
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
