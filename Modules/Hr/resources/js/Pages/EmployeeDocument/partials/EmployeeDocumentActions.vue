<script setup lang="ts">
type Model = EmployeeDocument;
const props = defineProps<{
	document: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(
		route("employee-document.destroy", [props.document])
	);
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrEmployeeDocumentForm :model="document">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrEmployeeDocumentForm>
			<AlertDialog
				:title="$t('remove-document')"
				:description="$t('are-you-sure-you-want-to-remove-this-document')"
				:name="document.name"
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
