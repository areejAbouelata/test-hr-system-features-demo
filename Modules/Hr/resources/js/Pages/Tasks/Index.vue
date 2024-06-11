<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    tasks?: PaginatedData<{
        id: number | string;
        name: string;
        description: string;
        employee_id:string;
        created_at: string;
        created_by: string;
        updated_by: string;
        translations: Record<string, { name: string }>;

    }>;
    employees?:{
        name: string;
        id: number | string;
    }


}>();

const locales = ["ar", "en"];

const breadcrumbs: BreadCrumbs = [
    {
        title: "Tasks",
        href: route("tasks.index"),
    },
];

const formId = "task-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.tasks,
    ),
    description: undefined,
    employee_id: undefined,

});

const updateformId = "task-update-form";
const updateForm = useForm({
    task_id: undefined,
    ...getTranslatableData(
        { name: "", },
        props.tasks,
    ),
    description: undefined,
    employee_id: undefined,
   
});

const EditTask = (task): void => {
    showUpdateForm.value = true;

    updateForm.task_id = task.id;
    for (const locale of task.translations) {

        updateForm[locale.locale].name = locale.name;

    }

    updateForm.description = task.description;
    updateForm.employee_id = task.employee_id;


};






const showCreateForm = ref(false);
const submit = () => {
    form.post(route("tasks.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`tasks/${updateForm.task_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};


const deleteTask = async (task_id: number) => {
    updateForm.delete(`tasks/${task_id}`)
};
const softdeleteTask = async (task_id: number) => {
    updateForm.delete(`tasks/${task_id}/softRestore`)
};


const headers = [
    "name",
    "description",
    "employee_id",
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
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>
                               
                                <formField label="description" :error="form.errors.description">
                                    <Input v-model="form.description" />
                                </formField>
                                <formField label="employee" :error="form.errors.description">
                                   <Select v-model="form.employee_id" :options="employees" option-value="id" option-label="ar_username"/> 
                                </formField>


                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="task-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="Edit Task">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">

                                <input v-model="updateForm.leave_id" type="hidden" />

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
                                        <Input v-model="updateForm[locale].name" />
                                    </FormField>
                                </template>
                               
                                <formField label="description" :error="updateForm.errors.description">
                                    <Input v-model="updateForm.description" />
                                </formField>
                                <formField label="employee" :error="updateForm.errors.description">
                                   <Select v-model="updateForm.employee_id" :options="employees" option-value="id" option-label="ar_username"/> 
                                </formField>
                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="task-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>


            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="tasks">
                    <template #row="{ row }">
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>
                        <TableCell>
                            {{ row?.description }}
                        </TableCell>
                        <TableCell>
                            {{ row?.employee_id }}
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
