<script setup lang="ts">
type Model = Employee;

const props = defineProps<{
	employee: Model;
}>();

const breadcrumbs = computed<Breadcrumbs>(() => [
	{ label: "employees", href: route("employees.index") },
	{
		label: props.employee.name,
		href: route("employees.show", [props.employee]),
	},
]);
</script>

<template>
	<div>
		<Header :title="employee.name" :breadcrumbs>
			<template #actions>
				<EmployeesActions :employee />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardHeader class="justify-between">
					<CardTitle>{{ $t("employee-details") }}</CardTitle>
					<Button
						:href="route('employees.edit', [employee])"
						icon="i-tabler-pencil"
						color="gray"
						variant="outline"
						:label="$t('edit')"
					/>
				</CardHeader>
				<CardContent>
					<DataGrid>
						<DataGridItem label="name" :value="employee.name" />
						<DataGridItem label="name" :value="employee.name" />
						<DataGridItem label="name" :value="employee.name" />
						<DataGridItem label="name" :value="employee.name" />
					</DataGrid>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
