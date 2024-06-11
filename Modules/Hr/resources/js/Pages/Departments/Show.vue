<script setup lang="ts">


const props = defineProps<{


	department_employees?: PaginatedData<{
		name: string;
		id: number | string;
	}>;

	department?: {
		id: number | string;
		name: string;
		description: string;
		unit_manager_id: number | string;
		unit_manager_name: string;
		updated_at: string;
		created_at: string;
		created_by: string;
		updated_by: string;
	};

	employees?: {
		name: string;
		id: number | string;
	};
}>();

const breadcrumbs: BreadCrumbs = [
	{
		title: "Department Employees",
		href: route("departments.index"),
	},
	/*   {
		  title: props.unit?.name,
		  href: route("departments.show", props.unit?.id),
	  } */
];

const updateformId = "department-update-form";
const updateForm = useForm({
	department_id: props.department.id,

	employee_id: [],
});


const showUpdateForm = ref(false);
const update = () => {
	updateForm.put(`/departments/${props.department.id}/employees`),
	{
		onSuccess: () => {
			showUpdateForm.value = false;
			updateForm.reset();
		},
	};
};

const removeEmployee = async (employee_id: number) => {
	updateForm.put(`/departments/${employee_id}/employees/remove`);
};

const headers = ["الاسم", "اخر تعديل", "الاجراءات"];
</script>
<template>
	<div>
		<Header title="جدول الاقسام" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showUpdateForm" title="إضافة موظف">
					<template #trigger>
						<Button variant="success" label="إضافة موظف" icon="i-tabler-plus" />
					</template>
					<div class="sheet-body">
						<div class="panel">
							<Form :id="updateformId" @submit="update">
								<FormField label="" :error="updateForm.errors.unit_id">
									<Input v-model="updateForm.unit_id" type="hidden" />
								</FormField>

								<formField label="اختار موظف" :error="updateForm.errors.employee_id">
									<Combobox v-model="updateForm.employee_id" :options="employees"
										option-label="ar_username" option-value="id" :multiple="true" />
								</formField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button form="department-update-form" type="submit" label="حفظ" :loading="updateForm.processing" />
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>

		<div class="page-content">
			<div class="panel">
				<DataTable :headers :data="department_employees">
					<template #row="{ row }">
						<TableCell>
							{{ row.name }}

						</TableCell>
						<TableCell>

						</TableCell>

						<TableCell>
							<DropdownMenu>
								<DropdownMenuTrigger as-child>
									<Button variant="light-ghost" icon="i-tabler-dots" />
								</DropdownMenuTrigger>
								<DropdownMenuContent>
									<AlertDialog title="حذف الموظف" description="هل أنت متأكد من حذف هذه الموظف؟"
										@confirm="() => removeEmployee(row.id)" :loading="false">
										<DropdownMenuItem @select.prevent="" label="حذف الموظف" icon="i-tabler-trash"
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
