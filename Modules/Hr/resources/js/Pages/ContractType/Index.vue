<script setup lang="ts">
type Model = ContractType;
const props = defineProps<{
	contractTypes: PaginatedData<Model>;
}>();
const breadcrumbs = computed<Breadcrumbs>(() => [
	{
		label: trans("contract-types"),
		href: route("contract-type.index"),
	},
]);

const headers = computed<DataTableHeaders<keyof Model>>(() => [
	{
		key: "name",
		label: trans("name"),
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
		<Header :title="$t('contract-types')" :breadcrumbs="breadcrumbs">
			<template #actions>
				<HrContractTypeForm />
			</template>
		</Header>
		<div class="page-content">
			<Card>
				<CardContent>
					<DataTable :headers :data="contractTypes">
						<template #row="{ row: contractType }">
							<TableCell>{{ contractType.name }}</TableCell>

							<TableCell>
								<BooleanBadge :value="contractType.is_active" />
							</TableCell>
							<TableCell>
								<HrContractTypeActions :contractType size="sm" />
							</TableCell>
						</template>
					</DataTable>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
