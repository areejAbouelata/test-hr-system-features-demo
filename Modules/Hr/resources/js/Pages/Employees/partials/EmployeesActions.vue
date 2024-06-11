<script setup lang="ts">
type Model = Employee;
const props = defineProps<{
	employee: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("employees.destroy", [props.employee]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<DropdownMenuItem
				@select.prevent=""
				:label="$t('edit')"
				icon="i-tabler-pencil"
				:href="route('employees.edit', [props.employee])"
			/>
			<AlertDialog
				:title="$t('remove-employee')"
				:description="$t('are-you-sure-you-want-to-remove-this-employee')"
				:name="employee.name"
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
