<script setup lang="ts">
const props = defineProps<{
	units?: PaginatedData<{
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
		};
		translations: { name: string, description: string };

	}>;

	employees?: {
		name: string;
		id: number | string;
	};
}>();

const breadcrumbs: BreadCrumbs = [
	{
		title: "الوحدات",
		href: route("units.index"),
	},
];

const formId = "unit-create-form";
const form = useForm({
	...getTranslatableData(
		{ name: "", },

		props.units,
	),
	unit_manager_id: undefined,
});

const updateformId = "unit-update-form";
const updateForm = useForm({
	unit_id: undefined,
	...getTranslatableData(
		{ name: "", },

		props.units,
	),
	unit_manager_id: undefined,
});

const AssignUser = (unit): void => {
	showUpdateForm.value = true;

	updateForm.unit_id = unit.id;
	for (const locale of unit.translations) {

		updateForm[locale.locale].name = locale.name;
		updateForm[locale.locale].description = locale.description;

	}

	updateForm.unit_manager_id = unit.unit_manager_id;
};

const showCreateForm = ref(false);
const submit = () => {
	form.post(route("units.store"), {
		onSuccess: () => {
			form.reset();
			showCreateForm.value = false;
		},
	});
};

const showUpdateForm = ref(false);
const update = () => {
	updateForm.put(`units/${updateForm.unit_id}`),
	{
		onSuccess: () => {
			showUpdateForm.value = false;
			updateForm.reset();
		},
	};
};

const deleteUnit = async (unit_id: number) => {
	updateForm.delete(`units/${unit_id}`);
};
const softDeleteUnit = async (unit_id: number) => {
	updateForm.delete(`units/${unit_id}`);
};

const headers = ["الاسم", "الوصف", "رئيس الوحدة", "اخر تعديل", "الاجراءات"];
</script>
<template>
	<div>
		<Header title="جدول الوحدات" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showCreateForm" title="إضافة وحدة">
					<template #trigger>
						<Button variant="success" label="إضافة وحدة" icon="i-tabler-plus" />
					</template>
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="submit">
								<template v-for="(locale, index) in $page.props.default_locales" :key="locale">

									<FormField :label="`name ${locale}`" :error="form.errors?.[`${locale}.name`]">
										<Input v-model="form[locale].name" />
									</FormField>


								</template>
								<template v-for="(locale, index) in $page.props.default_locales" :key="locale">
									<FormField :label="`description ${locale}`"
										:error="form.errors?.[`${locale}.description`]">
										<Input v-model="form[locale].description" />
									</FormField>
								</template>
								<formField label="تعين رئيس وحدة" :error="form.errors.unit_manager_id">
									<Combobox v-model="form.unit_manager_id" :options="employees"
										option-label="ar_username" option-value="id" />
								</formField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button form="unit-create-form" type="submit" label="حفظ" :loading="form.processing" />
						<SheetClose />
					</template>
				</Sheet>
				<Sheet v-model:open="showUpdateForm" title="تعديل وحدة">
					<div class="sheet-body">
						<div class="panel">
							<Form :id="updateformId" @submit="update">
								<FormField label="" :error="updateForm.errors.unit_id">
									<Input v-model="updateForm.unit_id" type="hidden" />
								</FormField>
								<template v-for="(locale, index) in $page.props.default_locales" :key="locale">

									<FormField :label="`name ${locale}`" :error="updateForm.errors?.[`${locale}.name`]">
										<Input v-model="updateForm[locale].name" />
									</FormField>


								</template>
								<template v-for="(locale, index) in $page.props.default_locales" :key="locale">
									<FormField :label="`description ${locale}`"
										:error="updateForm.errors?.[`${locale}.description`]">
										<Input v-model="updateForm[locale].description" />
									</FormField>
								</template>

								<formField label="تعين رئيس وحدة" :error="form.errors.unit_manager_id">
									<Combobox v-model="updateForm.unit_manager_id" :options="employees"
										option-label="ar_username" option-value="id" />
								</formField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button form="unit-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>

		<div class="page-content">
			<div class="panel">

				<DataTable :headers :data="units">
					<template #row="{ row }">
						<TableCell>
							<a :href="route('units.show', { unit: row.id })">
								{{ row?.name }}</a>
						</TableCell>
						<TableCell>
							{{ row?.description }}
						</TableCell>
						<TableCell>
							{{ row?.unit_manager_name }}
						</TableCell>
						<TableCell>
							<div v-if="row?.created_by">
								{{ row?.created_by }}
							</div>
							<div v-else-if="row?.updated_by">
								{{ row?.updated_by }}
							</div>
						</TableCell>

						<TableCell>
							<DropdownMenu>
								<DropdownMenuTrigger as-child>
									<Button variant="light-ghost" icon="i-tabler-dots" />
								</DropdownMenuTrigger>
								<DropdownMenuContent>
									<DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="AssignUser(row)" />
									<AlertDialog title="حذف الوحدة النهائي"
										description="هل أنت متأكد من حذف هذه الوحدة؟"
										@confirm="() => deleteUnit(row.id)" :loading="false">
										<DropdownMenuItem @select.prevent="" label=" حذف الوحدة النهائي"
											icon="i-tabler-trash" :loading="false" :ui="{ icon: 'text-red-500' }" />
									</AlertDialog>
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
