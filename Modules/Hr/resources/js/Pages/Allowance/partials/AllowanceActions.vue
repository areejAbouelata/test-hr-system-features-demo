<script setup lang="ts">
type Model = Allowance;
const props = defineProps<{
	allowance: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("allowance.destroy", [props.allowance]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrAllowanceForm :model="allowance">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrAllowanceForm>
			<AlertDialog
				:title="$t('remove-allowance')"
				:description="$t('are-you-sure-you-want-to-remove-this-allowance')"
				:name="allowance.name"
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
