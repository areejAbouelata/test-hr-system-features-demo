<script setup lang="ts">
const props = defineProps<{
    requests?: PaginatedData<{
        id: number | string;
        name: string;
        description: string;
        unit_manager_id: number | string;
        unit_manager_name: string;
        updated_at: string;
        created_at: string;
        created_by: string;
        updated_by: string;
        employees?: {
            name: string;
            id: number | string;
        }
        translations: Record<string, { name: string }>;

    }>;


    employees?: {
        name: string;
        id: number | string;
    }



}>();

const breadcrumbs: BreadCrumbs = [
    {
        title: "انواع الطلبات",
        href: route("request_types.index"),
    },
];
const fieldType = ['select', 'multiSelect', 'text', 'number', 'date', 'file'];
const locales = ["ar", "en"];

const formId = "request-create-form";
const form = useForm({
    ...getTranslatableData(
        { name: "", },
        props.requests,
    ), description: undefined,
    fields: [] as Array<{
        label: string;
        type: string;
        required: boolean;
        options: Array<{ label: string; start_date: string; end_date: string }> | null;

    }>,

    options: undefined,
    label: undefined,
    isRequired: false,
});

const updateformId = "request-update-form";
const updateForm = useForm({
    request_type_id: undefined,
    ...getTranslatableData(
        { name: "", },
        props.requests,
    ),
    description: undefined,
    fields: [] as Array<{
        label: string;
        type: string;
        required: boolean;
        options: Array<{ label: string; start_date: string; end_date: string }> | null;

    }>,

    options: undefined,
    label: undefined,
    isRequired: false,


});



const EditRequestType = (requestType): void => {
    showUpdateForm.value = true;
    updateForm.request_type_id = requestType.id;
    for (const locale of requestType.translations) {

        updateForm[locale.locale].name = locale.name;

    } 
    updateForm.description = requestType.description;

    //peter make sure that fields are populated correctly. its not working as it should.
    updateForm.fields = requestType.fields;


};




const showCreateForm = ref(false);
const submit = () => {
    form.post(route("request_types.store"), {
        onSuccess: () => {
            form.reset();
            showCreateForm.value = false;
        },
    });
};

const showUpdateForm = ref(false);
const update = () => {
    updateForm.put(`request_types/${updateForm.request_type_id}`), {
        onSuccess: () => {
            showUpdateForm.value = false;
            updateForm.reset();
        },
    };
};


const deleteUnit = async (request_type_id: number) => {
    updateForm.delete(`requestTypes/${request_type_id}`)
};
const softDeleteUnit = async (request_type_id: number) => {
    updateForm.delete(`request_types/${request_type_id}`)
};


function addField() {
    form.fields.push({ label: '', type: 'text', required: false });
}

function removeField(index: number) {
    form.fields.splice(index, 1);
}

function addOption(fieldIndex) {
    const options = form.fields[fieldIndex].options || [];

    options.push({ label: '' });
    form.fields[fieldIndex].options = options;

}
function addEditField() {
    updateForm.fields.push({ label: '', type: 'text', required: false });
}

function removeEditField(index: number) {
    updateForm.fields.splice(index, 1);
}

function removeOption(fieldIndex, optionIndex) {
    const options = form.fields[fieldIndex].options;
    if (options) {
        options.splice(optionIndex, 1);
    }
}
function addEditOption(fieldIndex) {
    const options = updateForm.fields[fieldIndex].options || [];

    options.push({ label: '' });
    updateForm.fields[fieldIndex].options = options;

}

function removeEditOption(fieldIndex, optionIndex) {
    const options = updateForm.fields[fieldIndex].options;
    if (options) {
        options.splice(optionIndex, 1);
    }
}

const headers = [
    "name",
    "description",
    "last action",
    "actions",

];
</script>
<template>
    <div>

        <Header title="جدول انواع الطلبات" :breadcrumbs>
            <template #actions>
                <Sheet v-model:open="showCreateForm" title="إضافة  نوع طلب">
                    <template #trigger>
                        <Button variant="success" label="إضافة نوع طلب" icon="i-tabler-plus" />
                    </template>
                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="formId" @submit="submit">

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
                                        <Input v-model="form[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="الوصف" :error="form.errors.description">
                                    <Input v-model="form.description" />
                                </formField>
                                <button type="button" @click="addField">Add Field</button>

                                <div v-for="(field, index) in form.fields" :key="index">
                                    <Input v-model="field.label" placeholder="Field Label" />
                                    <Select v-model="field.type" :options="fieldType" />
                                    <div
                                        v-if="field.type === 'select' || field.type === 'multiSelect' || field.type === 'date'">
                                        <div v-for="(option, optionIndex) in field.options" :key="optionIndex">
                                            <div v-if="field.type === 'select' || field.type === 'multiSelect'">
                                                <Input v-model="option.label" placeholder="Option Label" />
                                            </div>
                                            <div v-else-if="field.type === 'date'">
                                                <DateInput v-model="option.start_date" />
                                                <DateInput v-model="option.end_date" />
                                            </div>

                                            <Button @click="removeOption(index, optionIndex)">Remove Option</Button>
                                        </div>
                                        <Button @click="addOption(index)">Add
                                            Option</Button>
                                    </div>


                                    <input type="checkbox" v-model="field.required">
                                    <button @click="removeField(index)">Remove</button>


                                </div>


                            </Form>



                        </div>
                    </div>
                    <template #footer>
                        <Button form="request-create-form" type="submit" label="حفظ" :loading="form.processing" />
                        <SheetClose />
                    </template>
                </Sheet>
                <Sheet v-model:open="showUpdateForm" title="تعديل وحدة">

                    <div class="sheet-body">
                        <div class="panel">
                            <Form :id="updateformId" @submit="update">
                                <Input v-model="updateForm.request_type_id" type="hidden" />

                                <template v-for="(locale, index) in $page.props.default_locales" :key="locale">
                                    <FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
                                        <Input v-model="updateForm[locale].name" />
                                    </FormField>
                                </template>

                                <formField label="الوصف" :error="updateForm.errors.description">
                                    <Input v-model="updateForm.description" />
                                </formField>
                                <button type="button" @click="addEditField">Add Field</button>

                                <div v-for="(field, index) in updateForm.fields" :key="index">
                                    <Input v-model="field.label" placeholder="Field Label" />
                                    <Select v-model="field.type" :options="fieldType" />
                                    <div>
                                        <div v-for="(option, optionIndex) in field.options" :key="optionIndex">
                                            <div v-if="field.type === 'select' || field.type === 'multiSelect'">
                                                <Input v-model="option.label" placeholder="Option Label" />

                                            </div>
                                            <div v-else-if="field.type === 'date'">
                                                <DateInput v-model="option.start_date" />
                                                <DateInput v-model="option.end_date" />
                                            </div>
                                            <Button @click="removeEditOption(index, optionIndex)">Remove Option</Button>
                                        </div>
                                        <Button @click="addEditOption(index)">Add
                                            Option</Button>
                                    </div>


                                    <input type="checkbox" v-model="field.required">
                                    <button @click="removeEditField(index)">Remove</button>


                                </div>



                            </Form>
                        </div>
                    </div>
                    <template #footer>
                        <Button form="request-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
                        <SheetClose />
                    </template>
                </Sheet>

            </template>
        </Header>

        <div class="page-content">
            <div class="panel">

                <DataTable :headers :data="requests">
                    <template #row="{ row }">
                        <!--   <TableCell>
                            <a :href="route('units.show', { unit: row.id })"> {{ row?.name }}</a>
                        </TableCell> -->
                        <TableCell>
                            {{ row?.name }}
                        </TableCell>
                        <TableCell>
                            {{ row?.description }}
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

                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil"
                                        @click="EditRequestType(row)" />

                                    <AlertDialog title="حذف الوحدة" description="هل أنت متأكد من حذف هذه الوحدة؟"
                                        @confirm="() => softDeleteUnit(row.id)" :loading="false">
                                        <DropdownMenuItem @select.prevent="" label="حذف الوحدة" icon="i-tabler-trash"
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
