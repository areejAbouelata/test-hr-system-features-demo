<script setup lang="ts">
import type FormField from "@/Components/ui/form/FormField.vue";

type Model = Employee;
const props = defineProps<{
	mangers: Users;
	countries: Countries;
	jobTitles: JobTitles;
	departments: Departments;
	jobLevels: Users;
	branches: Users;
	allowances: Users;
	shifts: Users;
	employee?: Model;
}>();

const breadcrumbs = computed<Breadcrumbs>(() => [
	{ label: trans("employees"), href: route("employees.index") },
	{ label: trans("add-employee"), href: route("employees.create") },
]);

const genders = computed(() => [
	{ label: trans("male"), value: "male" },
	{ label: trans("female"), value: "female" },
]);
const formId = `${uuid()}-form`;
const form = useForm<ModelForm<Model>>({
	//general data
	name: props.employee?.name ?? "",
	email: props.employee?.email ?? "",
	password: props.employee?.password ?? "",
	note: props.employee?.note ?? "",
	is_ban: false,
	birth_date: props.employee?.birth_date ?? undefined,
	gender: props.employee?.gender ?? genders.value[0].value,
	country_id: props.employee?.country_id ?? undefined,
	//iqama data
	civilization_state: props.employee?.civilization_state ?? "",
	employing_number: props.employee?.employing_number ?? "",
	id_number: props.employee?.id_number ?? "",
	nationality: props.employee?.nationality ?? "",
	iqama_expiration_date: props.employee?.iqama_expiration_date ?? undefined,
	passport: props.employee?.passport ?? "",
	attachments: props.employee?.attachments ?? [],
	attachment_name: props.employee?.attachment_name ?? [],
	address: props.employee?.address ?? "",
});

const store = () => {
	form.post(route("branches.store"), {
		onSuccess: () => {
			form.reset();
		},
	});
};

const submit = () => {
	store();
};
</script>

<template>
	<div>
		<Header :title="$t('add-employee')" :breadcrumbs />
		<div class="page-content">
			<Card>
				<CardContent>
					<Form @submit="submit" :id="formId">
						<Fieldset :label="$t('general-data')" grid>
							<BranchesField :form="form" />
							<FormField :label="$t('name')" :error="form.errors.name">
								<Input v-model="form.name" />
							</FormField>
							<FormField :label="$t('email')" :error="form.errors.email">
								<Input v-model="form.email" />
							</FormField>
							<FormField
								:label="$t('password')"
								:error="form.errors.password"
							>
								<PasswordInput v-model="form.password" />
							</FormField>
							<FormField :label="$t('note')" :error="form.errors.note">
								<Textarea v-model="form.note" rows="2" />
							</FormField>
							<FormField
								:label="$t('birth-date')"
								:error="form.errors.birth_date"
							>
								<DateInput v-model="form.birth_date" />
							</FormField>
							<FormField
								:label="$t('gender')"
								:error="form.errors.gender"
							>
								<Combobox
									v-model="form.gender"
									:options="genders"
									option-label="label"
									option-value="value"
								/>
							</FormField>
							<CountryField :form />
						</Fieldset>
						<Fieldset :label="$t('iqama-data')" grid>
							<FormField
								:label="$t('civilization-state')"
								:error="form.errors.civilization_state"
							>
								<Input v-model="form.civilization_state" />
							</FormField>
							<FormField
								:label="$t('employing-number')"
								:error="form.errors.employing_number"
							>
								<Input v-model="form.employing_number" />
							</FormField>
							<FormField
								:label="$t('id-number')"
								:error="form.errors.id_number"
							>
								<Input v-model="form.id_number" />
							</FormField>
							<FormField
								:label="$t('nationality')"
								:error="form.errors.nationality"
							>
								<Input v-model="form.nationality" />
							</FormField>
							<FormField
								:label="$t('iqama-expiration-date')"
								:error="form.errors.iqama_expiration_date"
							>
								<DateInput v-model="form.iqama_expiration_date" />
							</FormField>
							<FormField
								:label="$t('passport')"
								:error="form.errors.passport"
							>
								<Input v-model="form.passport" />
							</FormField>
							<!-- <FormField
								:label="$t('attachments')"
								:error="form.errors.attachments"
							>
								<AttachmentsInput v-model="form.attachments" />
							</FormField>
							<FormField
								:label="$t('attachment-name')"
								:error="form.errors.attachment_name"
							>
								<AttachmentsInput v-model="form.attachment_name" />
							</FormField> -->
							<FormField
								:label="$t('address')"
								:error="form.errors.address"
							>
								<Input v-model="form.address" />
							</FormField>
						</Fieldset>
					</Form>
				</CardContent>
			</Card>
		</div>
	</div>
</template>

<style></style>
