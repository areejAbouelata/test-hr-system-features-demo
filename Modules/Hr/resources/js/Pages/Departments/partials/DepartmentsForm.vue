<script setup lang="ts">
type Model = Department;

declare module "@inertiajs/core" {
	interface PageProps {
		all_departments: Departments;
		employees: Employees;
	}
}
const emit = defineEmits(["close"]);
const props = defineProps<{
	model?: Model;
}>();

const isUpdating = computed(() => !!props.model);

const showForm = defineModel<boolean>("open");

const formId = `${uuid()}-form`;
const uiLabels = computed(() => {
	return {
		formTitle: isUpdating.value
			? trans("update-department")
			: trans("create-department"),
		triggerLabel: isUpdating.value ? trans("update") : trans("create"),
		submitLabel: isUpdating.value ? trans("update") : trans("create"),
	};
});

const { locales } = useLocales();
const form = useForm<Partial<Omit<Model, "id">>>(() => ({
	...getTranslatableData<Model>(
		{ name: "", description: "", short_name: "" },
		props.model?.translations,
		locales.value
	),
	head_of_department: props.model?.head_of_department ?? undefined,
	parent_department_id: props.model?.parent_department_id ?? undefined,
	is_active: props.model?.is_active ?? true,
	branch_id: props.model?.branch_id ?? undefined,
}));

const submitOptions: VisitOptions = {
	onSuccess: () => {
		showForm.value = false;
		form.reset();
	},
};
const store = () => {
	form.post(route("departments.store"), submitOptions);
};
const update = () => {
	form.patch(route("departments.update", [props.model]), submitOptions);
};
const submit = () => {
	if (isUpdating.value) {
		update();
	} else {
		store();
	}
};

watch(
	() => props.model,
	() => {
		form.reset();
	}
);
const handleOpenUpdate = (open: boolean) => {
	if (!open) {
		emit("close");
		form.reset();
	}
};
</script>

<template>
	<Dialog
		v-model:open="showForm"
		@update:open="handleOpenUpdate"
		:title="uiLabels.formTitle"
	>
		<template #trigger>
			<slot>
				<Button
					color="green"
					:label="uiLabels.triggerLabel"
					:icon="isUpdating ? 'i-tabler-pencil' : 'i-tabler-plus'"
				/>
			</slot>
		</template>
		<div class="">
			<Form :id="formId" @submit="submit">
				<BranchesField :form="form" />
				<TranslatableField :label="$t('name')" :form form-key="name">
					<Input />
				</TranslatableField>
				<TranslatableField
					:label="$t('short-name')"
					:form
					form-key="short_name"
				>
					<Input />
				</TranslatableField>
				<TranslatableField
					:label="$t('description')"
					:form
					form-key="description"
				>
					<Input />
				</TranslatableField>
				<FormField
					:label="$t('head-of-department')"
					:error="form.errors.head_of_department"
				>
					<Combobox
						:options="$page.props.employees"
						option-label="name"
						option-value="id"
						v-model="form.head_of_department"
					/>
				</FormField>
				<FormField
					:label="$t('parent-department')"
					:error="form.errors.parent_department_id"
				>
					<Combobox
						:options="$page.props.all_departments"
						option-label="name"
						option-value="id"
						v-model="form.parent_department_id"
					/>
				</FormField>
				<StatusField :form="form" />
			</Form>
		</div>
		<template #footer>
			<DialogClose />
			<Button
				:form="formId"
				type="submit"
				:label="uiLabels.submitLabel"
				:loading="form.processing"
			/>
		</template>
	</Dialog>
</template>

<style></style>
