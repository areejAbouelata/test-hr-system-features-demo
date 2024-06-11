<script setup lang="ts">
import type DateInput from '@/Components/ui/date-input/DateInput.vue';
import TableCell from '@/Components/ui/table/TableCell.vue';

const props = defineProps<{
    vacations?: PaginatedData<{
        id: number | string;
        name: string;
        start_date: string;
        end_date: string;
        created_at: string;
        created_by: string;
        updated_by: string;
        translations: Record<string, { name: string }>;

    }>;
    employees?: {
        name: string;
        id: number | string;
    }
    units?: {
        name: string;
        id: number | string;
    }
    departments?: {
        name: string;
        id: number | string;
    }


}>();
const locales = ["ar", "en"];

const breadcrumbs: BreadCrumbs = [
    {
        title: "Formal Vacations",
        href: route("personal_vacation.index"),
    },
];

const formId = "vacation-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.vacations,
    ),
    name: undefined,
    start_date: undefined,
    end_date: undefined,
    employee_id: []
});

const updateformId = "vacation-update-form";
const updateForm = useForm({
    ...getTranslatableData(
        { name: "", },
        props.vacations,
    ),
    vacation_id: undefined,
    name: undefined,
    start_date: undefined,
    end_date: undefined,
    employee_id: []


});

const EditVacation = (vacation): void => {
    showUpdateForm.value = true;

    updateForm.vacation_id = vacation.id;
/*     Peter I am not quite sure how to load up the translateable name to edited modal
 */ 
    for (const locale of vacation.translations) {

        updateForm[locale.locale].name = locale.name;

    }

    updateForm.start_date = vacation.start_date;
    updateForm.end_date = vacation.end_date;


};






const showCreateForm = ref(false);
const submit = () => {
    form.post(route("formal_vacation.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`formal_vacations/${updateForm.vacation_id}`),
    {
        onSuccess: () => {
            showUpdateForm.value = false;
        },
    };
};


const deleteVacation = async (vacation_id: number) => {
    updateForm.delete(`formal_vacations/${vacation_id}`)
};
const softdeleteVacation = async (vacation_id: number) => {
    updateForm.delete(`formal_vacations/${vacation_id}/softRestore`)
};


const headers = [
    "name",
    "date",
    "last_action",
    "actions",

];


</script>

<template>
    <div>
        <Header title="Formal Vacation Table" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="Add Vacation">
                    <template #trigger>
                        <Button variant="success" label="Add Vacation" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="start date" :error="form.errors.start_date">
                                    <DateInput v-model="form.start_date" />
                                </formField>
                                <formField label="end date" :error="form.errors.end_date">
                                    <DateInput v-model="form.end_date" />
                                </formField>
                                <formField label="Employees" :error="updateForm.errors.end_date">

                                    <combobox v-model="form.employee_id" :options="employees" :multiple="true"
                                        option-label="ar_username" option-value="id" label="الموظف"
                                        :error="form.errors.employee_id" />
                                </formField>

                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="vacation-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="Edit Vacation">
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">
                                <formField label="الاسم" :error="updateForm.errors.vacation_id">
                                    <Input v-model="updateForm.vacation_id" type="hidden" />
                                </formField>
                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
                                        <Input v-model="updateForm[locale].name" />
                                    </FormField>
                                </template>
                                <formField label="start date" :error="updateForm.errors.start_date">
                                    <DateInput v-model="updateForm.start_date" />
                                </formField>
                                <formField label="end date" :error="updateForm.errors.end_date">
                                    <DateInput v-model="updateForm.end_date" />
                                </formField>
                                <formField label="Employees" :error="updateForm.errors.end_date">
                                    <combobox v-model="updateForm.employee_id" :options="employees" :multiple="true"
                                        option-label="ar_username" option-value="id" label="employee"
                                        :error="updateForm.errors.employee_id" />
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
                            {{ row?.start_date }} - {{ row?.end_date }}
                        </TableCell>

                        <TableCell>

                            {{ row?.updated_by || row?.created_by }}


                        </TableCell>






                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost" size="icon" icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="EditVacation(row)" />


                                    <AlertDialog title="حذف الاجازة الرسمية النهائي"
                                        description="هل أنت متأكد من حذف هذه الاجازة الرسمية؟"
                                        @confirm="() => deleteVacation(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label=" حذف الاجازة الرسمية النهائي"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
                                    </AlertDialog>
                                    <AlertDialog title="حذف الاجازة الرسمية"
                                        description="هل أنت متأكد من حذف هذه الاجازة الرسمية؟"
                                        @confirm="() => softdeleteVacation(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الاجازة الرسمية"
                                            icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
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
