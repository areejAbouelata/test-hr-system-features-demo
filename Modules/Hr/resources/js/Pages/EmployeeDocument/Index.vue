<script setup lang="ts">
type Model = EmployeeDocument;
const props = defineProps<{
	documents: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("employee-documents"),
		href: route("employee-document.index"),
	},
]);

const headers = computed<DataTableHeaders<keyof Model>>(() => [
	{
		key: "name",
		label: trans("name"),
	},
	{
		key: "expiration_date",
		label: trans("expiration-date"),
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
		<Header :title="$t('employee-documents')" :breadcrumbs="breadcrumbs">
			<template #actions>
				<HrEmployeeDocumentForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="documents">
						<template #row="{ row: document }">
							<TableCell>{{ document.name }}</TableCell>
							<TableCell>
								<DatePreview :date="document.expiration_date" />
							</TableCell>

							<TableCell>
								<BooleanBadge :value="document.is_active" />
							</TableCell>
							<TableCell>
								<HrEmployeeDocumentActions :document size="sm" />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
