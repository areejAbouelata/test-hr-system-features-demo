<script setup lang="ts">
type Model = Department;
const props = defineProps<{
	departments: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("departments"),
		href: route("departments.index"),
	},
]);

const headers = computed<DataTableHeaders<keyof Model>>(() => [
	{
		key: "name",
		label: trans("name"),
	},
	{
		key: "short_name",
		label: trans("short-name"),
	},
	{
		key: "description",
		label: trans("description"),
	},
	{
		key: "is_active",
		label: trans("status"),
	},
	{ key: "actions", label: "actions" },
]);
</script>

<template>
	<div>
		<Header :title="$t('departments')" :breadcrumbs>
			<template #actions>
				<HrDepartmentsForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="departments">
						<template #row="{ row: department }">
							<TableCell>{{ department.name }}</TableCell>
							<TableCell>{{ department.short_name }}</TableCell>
							<TableCell class="text-truncate">
								{{ department.description }}
							</TableCell>
							<TableCell>
								<BooleanBadge :value="department.is_active" />
							</TableCell>
							<TableCell>
								<HrDepartmentsActions :department />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
