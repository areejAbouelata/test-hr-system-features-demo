<script setup lang="ts">
type Model = JobTitle;
const props = defineProps<{
	jobTitles: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("job-titles"),
		href: route("job-title.index"),
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
				<HrJobTitleForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="jobTitles">
						<template #row="{ row: jobTitle }">
							<TableCell>{{ jobTitle.title }}</TableCell>
							<TableCell>{{ jobTitle.desc ?? "-" }}</TableCell>

							<TableCell>
								<BooleanBadge :value="jobTitle.is_active" />
							</TableCell>
							<TableCell>
								<HrJobTitleActions :jobTitle size="sm" />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
