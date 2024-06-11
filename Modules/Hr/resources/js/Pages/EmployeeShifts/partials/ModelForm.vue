<script setup lang="ts">
import { v4 as uuid } from "uuid";

type Model = EmployeeShift;
const emit = defineEmits(["close"]);
const props = defineProps<{
    model?: Model;
    employee?: Employee;
}>();

const isUpdating = computed(() => !!props.model);

const showForm = defineModel<boolean>("open");

const CREATE_TITLE = "إضافة دوام";
const UPDATE_TITLE = "تعديل دوام";

const formId = `${uuid()}-form`;
const formTitle = computed(() =>
    isUpdating.value ? UPDATE_TITLE : CREATE_TITLE
);

type ModelForm = Partial<Omit<Model, "id"> & { employee_id: number }>;
const form = useForm<ModelForm>(() => ({
    employee_id: props.employee?.id,
    shift_id: undefined,
    day_date: null,

    started_at: undefined,
    ended_at: undefined,
}));
watch(
    () => props,
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
    form.post(route("shifts.employees.store"), submitOptions);
};
const update = () => {
    form.patch(
        route("finances.expense_categories.update", props.model),
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
                    label="إسناد دوام"
                    icon="i-tabler-plus"
                />
            </slot>
        </template>
        <div class="sheet-body">
            <div class="panel">
                {{ form.errors }}
                <Form :id="formId" @submit="submit">
                    <FormField label="" :error="form.errors.employee_id">
                        <Input v-model="form.employee_id" type="hidden" />
                    </FormField>

                    <FormField label="الدوام" :error="form.errors.shift_id">
                        <Combobox
                            v-model="form.shift_id"
                            :options="$page.props.shifts"
                            option-label="name"
                            option-value="id"
                        >
                            <template #option="{ option: shift }">
                                {{ shift.name }} ({{ shift.start_time }} -
                                {{ shift.end_time }})
                            </template>
                        </Combobox>
                    </FormField>

                    <FormField
                        label="تاريخ البداية"
                        :error="form.errors.started_at"
                    >
                        <DateInput v-model="form.started_at"> </DateInput>
                    </FormField>
                    <FormField
                        label="تاريخ النهاية"
                        :error="form.errors.ended_at"
                    >
                        <DateInput v-model="form.ended_at"> </DateInput>
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
