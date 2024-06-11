<script setup lang="ts">
type Model = JobLevel;
const props = defineProps<{
	jobLevel: Model;
}>();
const deleteModelForm = useForm({});
const deleteModel = () => {
	deleteModelForm.delete(route("job-level.destroy", [props.jobLevel]));
};
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger v-bind="$attrs" />
		<DropdownMenuContent>
			<HrJobLevelForm :model="jobLevel">
				<DropdownMenuItem
					@select.prevent=""
					:label="$t('edit')"
					icon="i-tabler-pencil"
				/>
			</HrJobLevelForm>
			<AlertDialog
				:title="$t('remove-job-level')"
				:description="$t('are-you-sure-you-want-to-remove-this-job-level')"
				:name="jobLevel.name"
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
