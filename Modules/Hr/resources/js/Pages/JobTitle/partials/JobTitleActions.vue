<script setup lang="ts">
type Model = JobTitle;
const props = defineProps<{
	jobTitle: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("job-title.destroy", [props.jobTitle]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrJobTitleForm :model="jobTitle">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrJobTitleForm>
			<AlertDialog
				:title="$t('remove-job-title')"
				:description="$t('are-you-sure-you-want-to-remove-this-job-title')"
				:name="jobTitle.title"
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
