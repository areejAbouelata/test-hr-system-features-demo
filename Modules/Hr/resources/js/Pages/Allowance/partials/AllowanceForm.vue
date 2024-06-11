<script setup lang="ts">
type Model = Allowance;

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
			? trans("update-allowance")
			: trans("create-allowance"),
		triggerLabel: isUpdating.value ? trans("update") : trans("create"),
		submitLabel: isUpdating.value ? trans("update") : trans("create"),
	};
});

const { locales } = useLocales();
const form = useForm<Partial<Omit<Model, "id">>>(() => ({
	...getTranslatableData<Model>(
		{ name: "", desc: "" },
		props.model?.translations,
		locales.value
	),
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
	form.post(route("allowance.store"), submitOptions);
};
const update = () => {
	form.patch(route("allowance.update", [props.model]), submitOptions);
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
				<TranslatableField :label="$t('description')" :form form-key="desc">
					<Input />
				</TranslatableField>

				<FormField :error="form.errors?.is_active">
					<Switch v-model:checked="form.is_active" :label="$t('active')" />
				</FormField>
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
