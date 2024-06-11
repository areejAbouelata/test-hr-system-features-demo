<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import ModelForm from "./partials/ModelForm.vue";
import TableCell from "@/Components/ui/table/TableCell.vue";

type Model = CorporationDocument;
const props = defineProps<{
    corporationDocuments: PaginatedData<Model>;
}>();

const breadcrumbs: BreadCrumbs = [
    {
        title: "مستندات المنشأة",
        href: route("hr.settings.corporation-document.index"),
    },
];

const headers: DataTableHeaders<keyof Model> = [
    { label: "رقم الملف", key: "doc_number" },
    { label: "اسم الملف", key: "document_name" },
    { label: "الوصف", key: "desc" },
    { label: "تاريخ الانتهاء (ميلادي)", key: "expiration_date" },
    { label: "إظهار في ملف الموظف", key: "show_in_employee" },
    "",
];

const showForm = ref(false);
const modelToEdit = ref<Model>();
const editModel = (model: Model) => {
    modelToEdit.value = model;
    showForm.value = true;
};

const deleteModelForm = useForm({});
const deleteModel = (model: Model) => {
    if (deleteModelForm.processing) return;
    deleteModelForm.delete(
        route("hr.settings.corporation-document.destroy", model),
        { preserveScroll: true }
    );
};

const { filterForm } = useFilter({
    // is_active: route().params?.is_active ?? false,
});
</script>

<template>
    <div>
        <Header title="تصنيفات المصروف" :breadcrumbs>
            <template #actions>
                <ModelForm
                    :model="modelToEdit"
                    @close="modelToEdit = undefined"
                    v-model:open="showForm"
                />
            </template>
        </Header>
        <div class="page-content">
            <div class="panel">
                <DataTable :headers :data="corporationDocuments">
                    <template #row="{ row }">
                        <TableCell v-text="row.doc_number" />
                        <TableCell v-text="row.document_name" />
                        <TableCell v-text="row.desc" />
                        <TableCell v-text="row.expiration_date" />
                        <TableCell v-text="row.show_in_employee" />
                        <TableCell>
                            <div class="flex gap-2 items-center">
                                <Button
                                    icon="i-tabler-pencil"
                                    variant="light-ghost"
                                    aria-label="edit"
                                    @click="editModel(row)"
                                />
                                <Button
                                    icon="i-tabler-arrow-down"
                                    variant="light-ghost"
                                    aria-label="download"
                                    as="a"
                                    :download="row.document_name"
                                    :href="row.attachment"
                                />
                                <AlertDialog
                                    title="حذف المستند"
                                    description="هل أنت متأكد من خذف هذا المستند"
                                    @confirm="() => deleteModel(row)"
                                    :loading="deleteModelForm.processing"
                                >
                                    <Button
                                        icon="i-tabler-trash"
                                        variant="error-ghost"
                                        aria-label="delete"
                                        :loading="deleteModelForm.processing"
                                    />
                                </AlertDialog>
                            </div>
                        </TableCell>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<style></style>
