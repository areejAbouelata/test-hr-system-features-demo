<script setup lang="ts">
type Model = Allowance;
const props = defineProps<{
	allowances: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("allowances"),
		href: route("allowance.index"),
	},
]);

const headers = computed<DataTableHeaders<keyof Model>>(() => [
	{
		key: "name",
		label: trans("name"),
	},
	{
		key: "desc",
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
		<Header :title="$t('allowances')" :breadcrumbs="breadcrumbs">
			<template #actions>
				<HrAllowanceForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="allowances">
						<template #row="{ row: allowance }">
							<TableCell>{{ allowance.name }}</TableCell>
							<TableCell>{{ allowance.desc ?? "-" }}</TableCell>

							<TableCell>
								<BooleanBadge :value="allowance.is_active" />
							</TableCell>
							<TableCell>
								<HrAllowanceActions :allowance size="sm" />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
