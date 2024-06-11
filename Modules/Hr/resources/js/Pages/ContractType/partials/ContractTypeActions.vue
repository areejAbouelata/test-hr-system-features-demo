<script setup lang="ts">
type Model = ContractType;
const props = defineProps<{
	contractType: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("contract-type.destroy", [props.contractType]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrContractTypeForm :model="contractType">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrContractTypeForm>
			<AlertDialog
				:title="$t('remove-contract-type')"
				:description="
					$t('are-you-sure-you-want-to-remove-this-contract-type')
				"
				:name="contractType.name"
				@confirm="() => deleteModel()"
				:loading="deleteModelForm.processing"
			>
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('delete')"
					icon="i-tabler-trash"
					color="red"
				/>
			</AlertDialog>
		</DropdownMenuContent>
	</DropdownMenu>
</template>

<style></style>
