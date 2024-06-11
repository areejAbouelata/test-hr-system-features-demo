<script setup lang="ts">
import dayjs from "dayjs";
import { v4 as uuid } from "uuid";

type Model = CorporationDocument;
const emit = defineEmits(["close"]);
const props = defineProps<{
    model?: Model;
}>();

const isUpdating = computed(() => !!props.model);

const showForm = defineModel<boolean>("open");

const CREATE_TITLE = "إضافة مستند";
const UPDATE_TITLE = "تعديل مستند";

const formId = `${uuid()}-form`;
const formTitle = computed(() =>
    isUpdating.value ? UPDATE_TITLE : CREATE_TITLE
);
const form = useForm<Partial<Omit<Model, "id">>>(() => ({
    doc_number: props.model?.doc_number ?? undefined,
    document_name: props.model?.document_name ?? "",
    desc: props.model?.desc ?? "",
    expiration_date: props.model?.expiration_date ?? undefined,
    show_in_employee: props.model?.show_in_employee ?? false,
    attachment: props.model?.attachment ?? undefined,
}));
watch(
    () => props.model,
    () => {
        form.reset();
    }
);
const submitOptions: VisitOptions = {
    onSuccess: () => {
        showForm.value = false;
        form.reset();
    },
};
const store = () => {
    form.post(route("hr.settings.corporation-document.store"), submitOptions);
};
const update = () => {
    form.patch(
        route("hr.settings.corporation-document.update", props.model),
        submitOptions
    );
};
const submit = () => {
    if (isUpdating.value) {
        update();
    } else {
        store();
    }
};

const handleOpenUpdate = (open: boolean) => {
    if (!open) {
        emit("close");
    }
};
</script>

<template>
    <Sheet
        v-model:open="showForm"
        @update:open="handleOpenUpdate"
        :title="formTitle"
    >
        <template #trigger>
            <slot name="trigger">
                <Button
                    variant="success"
                    label="إضافة تصنيف"
                    icon="i-tabler-plus"
                />
            </slot>
        </template>
        <div class="sheet-body">
            <div class="panel">
                <Form :id="formId" @submit="submit">
                    <FormField
                        label="رقم الملف"
                        :error="form.errors.doc_number"
                    >
                        <Input type="number" v-model="form.doc_number" />
                    </FormField>
                    <FormField
                        label="اسم الملف"
                        :error="form.errors.document_name"
                    >
                        <Input v-model="form.document_name" />
                    </FormField>
                    <FormField label="الوصف" :error="form.errors.desc">
                        <Textarea v-model="form.desc" />
                    </FormField>
                    <FormField
                        label="تاريخ انتهاء صلاحية ملف الشركة"
                        :error="form.errors.expiration_date"
                    >
                        <DateInput
                            v-model="form.expiration_date"
                            format="yyyy-MM-dd"
                            model-type="format"
                            :enable-time-picker="false"
                        />
                    </FormField>
                    <FormField
                        label="إظهار فى ملف الموظف"
                        :error="form.errors.show_in_employee"
                        layout="checkbox"
                    >
                        <Checkbox v-model:checked="form.show_in_employee" />
                    </FormField>
                    <FormField label="الملف" :error="form.errors.attachment">
                        <Input
                            type="file"
                            @change="
                                (event) =>
                                    (form.attachment = Array.from(
                                        event.target.files
                                    )[0])
                            "
                        />
                    </FormField>
                </Form>
            </div>
        </div>
        <template #footer>
            <Button
                :form="formId"
                type="submit"
                label="حفظ"
                :loading="form.processing"
            />
            <SheetClose />
        </template>
    </Sheet>
</template>

<style></style>
