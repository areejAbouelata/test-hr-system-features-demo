<script setup lang="ts">
const props = defineProps<{
	requests?: PaginatedData<{
		id: number | string;
		type: string;
		reason: string;
		user_id: number | string;
		user_name: string;
		short_name: string;
		status: string;
		created_at: string;
	}>;

	users?: {
		name: string;
		id: number | string;
	};
	employees?: {
		ar_username: string;
		id: number | string;
	};
}>();

const breadcrumbs: BreadCrumbs = [
	{
		title: "جدول الطلبات",
		href: route("requests.index"),
	},
];

const formId = "request-create-form";
const form = useForm({
	asset_id: undefined,
	name: undefined,
	description: undefined,
	short_name: undefined,
	user_id: undefined,
	status: undefined,
});

const updateformId = "request-update-form";
const updateForm = useForm({
	asset_id: undefined,
	name: undefined,
	short_name: undefined,
	description: undefined,
	user_id: undefined,
	status: undefined,
});

const AssignUser = (asset: Asset): void => {
	showUpdateForm.value = true;

	updateForm.asset_id = asset.id;
	updateForm.name = asset.name;
	updateForm.short_name = asset.short_name;
	updateForm.description = asset.description;
	updateForm.user_id = asset.user_id;
	updateForm.status = asset.status;
};

const showCreateForm = ref(false);
const submit = () => {
	form.post(route("departments.store"), {
		onSuccess: () => {
			showCreateForm.value = false;
		},
	});
};

const showUpdateForm = ref(false);
const update = () => {
	updateForm.put(`departments/${updateForm.asset_id}`),
		{
			onSuccess: () => {
				showUpdateForm.value = false;
			},
		};
};

const deleteDepartment = async (department_id) => {
	updateForm.delete(`departments/${department_id}`);
};
/*
const removeAsset = (asset_id: number): void => {


}; */

const headers = [
	"الاسم",
	"الوصف",
	"الاسم المختصر",
	"الحالة",
	"رئيس القسم",
	"تاريخ الانشاء",
];

const statusTypes = [
	{
		id: "unactive",
		name: "غير مستخدمة",
	},
	{
		id: "active",
		name: "مستخدمة",
	},
];
</script>

<template>
	<div>
		<Header title="جدول الطلبات" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showCreateForm" title="إضافة طلب">
					<template #trigger>
						<Button
							variant="success"
							label="إضافة طلب"
							icon="i-tabler-plus"
						/>
					</template>
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="submit">
								<formField
									label="الموظفين"
									:error="form.errors.short_name"
								>
									<Combobox
										v-model="form.user_id"
										:multiple="false"
										:options="employees"
										option-label="ar_username"
										option-value="id"
									/>
								</formField>
								<formField
									label="الاسم المختصر"
									:error="form.errors.short_name"
								>
									<Combobox
										v-model="form.user_id"
										:multiple="false"
										:options="users"
										option-label="name"
										option-value="id"
									/>
								</formField>

								<formField
									label="الاسم المختصر"
									:error="form.errors.short_name"
								>
									<Input v-model="form.short_name" />
								</formField>
								<formField
									label="الوصف"
									:error="form.errors.description"
								>
									<Input v-model="form.description" />
								</formField>
								<formField label="الحالة" :error="form.errors.status">
									<Select
										v-model="form.status"
										:options="statusTypes"
										option-value="id"
										option-label="name"
									>
										<template #option="{ option }">
											{{ option.name }}
										</template>
									</Select>
								</formField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="request-create-form"
							type="submit"
							label="حفظ"
							:loading="form.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
				<Sheet v-model:open="showUpdateForm" title="تعديل سلعة">
					<div class="sheet-body">
						<div class="panel">
							<Form :id="updateformId" @submit="update">
								<FormField label="" :error="updateForm.errors.asset_id">
									<Input v-model="updateForm.asset_id" type="hidden" />
								</FormField>
								<formField
									label="الاسم"
									:error="updateForm.errors.name"
								>
									<Input v-model="updateForm.name" />
								</formField>
								<formField
									label="الاسم المختصر"
									:error="updateForm.errors.short_name"
								>
									<Input v-model="updateForm.short_name" />
								</formField>
								<formField
									label="الوصف"
									:error="updateForm.errors.description"
								>
									<Input v-model="updateForm.description" />
								</formField>
								<formField
									label="الحالة"
									:error="updateForm.errors.status"
								>
									<Select
										v-model="updateForm.status"
										:options="statusTypes"
										option-value="id"
										option-label="name"
									>
										<template #option="{ option }">
											{{ option.name }}
										</template>
									</Select>
								</formField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="request-update-form"
							type="submit"
							label="حفظ"
							:loading="updateForm.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>

		<div class="page-content">
			<div class="panel">
				<DataTable :headers :data="requests">
					<template #row="{ row }">
						<TableCell>
							{{ row?.user_name }}
						</TableCell>
						<TableCell>
							{{ row?.type }}
						</TableCell>
						<TableCell>
							{{ row?.status }}
						</TableCell>
						<TableCell>
							{{ row?.reason }}
						</TableCell>
						<TableCell>
							<div v-if="row.user_id">
								{{ row?.user_name }}
							</div>
						</TableCell>

						<TableCell>
							{{ row?.created_at }}
						</TableCell>

						<TableCell>
							<DropdownMenu>
								<DropdownMenuTrigger as-child>
									<Button variant="light-ghost" icon="i-tabler-dots" />
								</DropdownMenuTrigger>
								<DropdownMenuContent>
									<DropdownMenuItem
										label="تعديل"
										icon="i-tabler-pencil"
										@click="AssignUser(row)"
									/>
									<DropdownMenuItem
										label="حذف"
										icon="i-tabler-trash"
										:ui="{ icon: 'text-red-500' }"
										@click="deleteDepartment(row.id)"
									/>
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
