<script setup lang="ts">
type Model = Department;
const props = defineProps<{
	department: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("departments.destroy", [props.department]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrDepartmentsForm :model="department">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrDepartmentsForm>
			<AlertDialog
				:title="$t('remove-department')"
				:description="$t('are-you-sure-you-want-to-remove-this-department')"
				:name="department.name"
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
