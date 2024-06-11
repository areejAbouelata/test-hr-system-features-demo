<script setup lang="ts">
type Model = Employee;
const props = defineProps<{
	employees: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: "employees",
		href: route("employees.index"),
	},
]);

const headers = computed<DataTableHeaders<keyof Model>>(() => [
	{
		key: "name",
		label: trans('name'),
	},
	{ key: "actions", label: "actions" },
]);
</script>

<template>
	<div>
		<Header :title="$t('employees')" :breadcrumbs>
			<template #actions>
				<Button
					color="green"
					:label="$t('add-employee')"
					:href="route('employees.create')"
				/>
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="employees">
						<template #row="{ row: employee }">
							<TableCell>{{ employee.name }}</TableCell>
							<TableCell>
								<HrEmployeesActions :employee />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
