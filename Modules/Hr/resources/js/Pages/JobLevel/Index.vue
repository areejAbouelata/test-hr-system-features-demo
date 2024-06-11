<script setup lang="ts">
type Model = JobLevel;
const props = defineProps<{
	jobLevels: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("job-levels"),
		href: route("job-level.index"),
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
		<Header :title="$t('job-levels')" :breadcrumbs="breadcrumbs">
			<template #actions>
				<HrJobLevelForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="jobLevels">
						<template #row="{ row: jobLevel }">
							<TableCell>{{ jobLevel.name }}</TableCell>
							<TableCell>{{ jobLevel.desc ?? "-" }}</TableCell>

							<TableCell>
								<BooleanBadge :value="jobLevel.is_active" />
							</TableCell>
							<TableCell>
								<HrJobLevelActions :jobLevel size="sm" />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
