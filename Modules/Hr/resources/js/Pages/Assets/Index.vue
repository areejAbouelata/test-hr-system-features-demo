<script setup lang="ts">
const props = defineProps<{
	assets?: PaginatedData<{
		id: number | string;
		name: string;
		description: string;
		user_id: number | string;
		user_name: string;
		created_at: string;
	}>;

	users?: {
		name: string;
		id: number | string;
	};
}>();

const breadcrumbs: BreadCrumbs = [
	{
		title: "جدول الدوامات",
		href: route("shifts.employees"),
	},
];

const formId = "asset-create-form";
const form = useForm({
	asset_id: undefined,
	name: undefined,
	description: undefined,
	user_id: undefined,
	status: undefined,
});

const updateformId = "asset-update-form";
const updateForm = useForm({
	asset_id: undefined,
	name: undefined,
	description: undefined,
	user_id: undefined,
	status: undefined,
});

const AssignUser = (asset: Asset): void => {
	showUpdateForm.value = true;

	updateForm.asset_id = asset.id;
	updateForm.name = asset.name;
	updateForm.description = asset.description;
	updateForm.user_id = asset.user_id;
	updateForm.status = asset.status;
};

const showCreateForm = ref(false);
const submit = () => {
	form.post(route("assets.store"), {
		onSuccess: () => {
			showCreateForm.value = false;
		},
	});
};

const showUpdateForm = ref(false);
const update = () => {
	updateForm.put(`assets/${updateForm.asset_id}`),
		{
			onSuccess: () => {
				showUpdateForm.value = false;
			},
		};
};

const isDeleteDialogOpen = ref(false);

const showDeleteConfirmation = () => {
	isDeleteDialogOpen.value = true;
	console.log(isDeleteDialogOpen.value);
};

const closeDeleteConfirmation = () => {
	isDeleteDialogOpen.value = false;
};

const deleteAsset = async () => {
	// Perform deletion logic here
	// This is a placeholder, replace it with your actual deletion logic
	console.log("Asset deleted");
	closeDeleteConfirmation();
};
/*
const removeAsset = (asset_id: number): void => {


}; */

const headers = [
	"اسم العهده",
	"وصف العهدة",
	"اسم الموظف",
	"الحالة",
	"تاريخ الانشاء",
];

const statusTypes = [
	{
		id: "inactive",
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
		<Header title="جدول العهود" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showCreateForm" title="إضافة سلعة">
					<template #trigger>
						<Button
							variant="success"
							label="إضافة عهدة"
							icon="i-tabler-plus"
						/>
					</template>
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="submit">
								<!--  <FormField label="" :error="form.errors.asset_id">
                                    <Input v-model="form.asset_id" type="hidden" />
                                </FormField> -->
								<formField label="الاسم" :error="form.errors.name">
									<Input v-model="form.name" />
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
								<FormField
									label="المستخدمون"
									:error="form.errors.user_id"
								>
									<Combobox
										v-model="form.user_id"
										:multiple="false"
										:options="users"
										option-label="name"
										option-value="id"
									/>
								</FormField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="asset-create-form"
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
								<FormField
									label="المستخدمون"
									:error="updateForm.errors.user_id"
								>
									<Combobox
										v-model="updateForm.user_id"
										:multiple="false"
										:options="users"
										option-label="name"
										option-value="id"
									/>
								</FormField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="asset-update-form"
							type="submit"
							label="حفظ"
							:loading="updateForm.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>
		<AlertDialog
			v-model:open="isDeleteDialogOpen"
			title="تأكيد الحذف"
			description="هل أنت متأكد أنك تريد حذف هذا الاصل؟"
			cancelLabel="الغاء"
			confirmLabel="تأكيد"
			theme="danger"
			@confirm="deleteAsset"
			@cancel="closeDeleteConfirmation"
		/>
		<div class="page-content">
			<div class="panel">
				<DataTable :headers :data="assets">
					<template #row="{ row }">
						<TableCell>
							{{ row?.name }}
						</TableCell>
						<TableCell>
							{{ row?.description }}
						</TableCell>

						<TableCell>
							<div v-if="row.user_id">
								{{ row?.user_name }}
							</div>
							<Button
								@click="AssignUser(row)"
								v-else
								variant="light-ghost"
								>تعيين مستخدم</Button
							>
						</TableCell>

						<TableCell>
							<Badge
								:label="row.status ? 'Active' : 'Inactive'"
								:variant="row.status ? 'success' : 'error'"
							/>
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
										@click="showDeleteConfirmation"
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
