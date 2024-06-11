<script setup lang="ts">
const props = defineProps<{
	employees?: PaginatedData<{
		employee_name: string;
		employee_id: string;
		attendance_id: string;
		sign_in: string;
		sign_out: string;
		status: string;
		created_at: string;
		work_hours: string;
		difference_between_work_hours: string;
		start_time: string;
		end_time: string;
	}>;

	shifts?: {
		name: string;
		id: number | string;
	};
}>();
console.log(props);
const breadcrumbs: BreadCrumbs = [
	{
		title: "الحضور والانصراف",
		href: route("shifts.employees.attendance"),
	},
];
const formId = "attendance-form";
const form = useForm({
	employee_id: undefined,
	shift_id: undefined,
	day_date: null,
});

const addSchedule = (employee_id) => {
	showCreateForm.value = true;

	form.employee_id = employee_id;
};
const UpdateSchedule = (employee_id) => {
	showCreateForm.value = true;

	form.employee_id = employee_id;
};

const signformId = "sign-form";
const signForm = useForm({
	employee_id: undefined,
	attendance_id: undefined,
	sign_in_time: undefined,
	sign_out_time: undefined,
});

const sign_in = (employee_id, attendance_id) => {
	showSignForm.value = true;

	signForm.employee_id = employee_id;
	signForm.attendance_id = attendance_id;
};

const RestDay = (attendance_id) => {
	axios
		.post(route(`attendance.restDay`, { attendance: attendance_id }), {})
		.then((response) => {
			// Handle success response if needed
			console.log("Attendance marked as rest day:", response.data);
		})
		.catch((error) => {
			// Handle error response if needed
			console.error(
				"Error marking attendance as rest day:",
				error.response.data
			);
		});
};
const RemoveSchedule = (attendance_id, employee_id) => {
	axios
		.post(route("attendance.removeEmployeeShift"), {
			attendance: attendance_id,
			employee: employee_id,
		})

		.catch((error) => {
			// Handle error response if needed
			console.error(
				"Error marking attendance as rest day:",
				error.response.data
			);
		});
};

const showCreateForm = ref(false);
const showSignForm = ref(false);

const submit = () => {
	form.post(route("attendance.store"), {
		onSuccess: () => {
			showCreateForm.value = false;
		},
	});
};
const signing = () => {
	signForm.put(route("attendance.sign", signForm.attendance_id), {
		onSuccess: () => {
			showSignForm.value = false;
		},
	});
};
const headers = [
	"اسم الموظف",
	"الحالة",
	"المجدول",
	"الحضور",
	"الانصراف",
	"مده العمل",
	"الفرق",
];
</script>

<template>
	<div>
		<Header title="الحضور والانصراف" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showCreateForm" title="إضافة دوام">
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="submit">
								<FormField label="" :error="form.errors.employee_id">
									<Input v-model="form.employee_id" type="hidden" />
								</FormField>

								<FormField label="الدوام" :error="form.errors.shift_id">
									<select v-model="form.shift_id">
										<option
											v-for="shift in shifts"
											:value="shift.id"
											:key="shift.id"
										>
											{{ shift.name }}
										</option>
									</select>
								</FormField>
							</Form>

							<Form> </Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="attendance-form"
							type="submit"
							label="حفظ"
							:loading="form.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
				<Sheet v-model:open="showSignForm" title="تسجيل حضور وانصراف">
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="signing">
								<FormField label="" :error="form.errors.employee_id">
									<Input
										v-model="signForm.employee_id"
										type="hidden"
									/>
								</FormField>
								<FormField label="" :error="form.errors.attendance_id">
									<Input
										v-model="signForm.attendance_id"
										type="hidden"
									/>
								</FormField>

								<FormField
									label="تسجيل الحضور"
									:error="signForm.errors.sign_in_time"
								>
									<DateInput v-model="signForm.sign_in_time" />
									<input v-model="signForm.sign_in_time" type="time" />
								</FormField>
								<FormField
									label="تسجيل الانصراف"
									:error="signForm.errors.sign_out_time"
								>
									<DateInput v-model="signForm.sign_out_time" />
									<input
										v-model="signForm.sign_out_time"
										type="time"
									/>
								</FormField>
							</Form>

							<Form> </Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="attendance-form"
							type="submit"
							label="حفظ"
							:loading="form.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>
		<div class="page-content">
			<div class="panel">
				<DataTable :headers :data="employees">
					<template #row="{ row }">
						<TableCell>
							{{ row?.employee_name }}
						</TableCell>
						<!-- <div class="div" v-for="attendance in row?.attendances" :key="attendance.id">

                        </div> -->
						<TableCell>
							<div v-if="row.status == 'attended'">
								<Badge label="حضور" variant="success" />
							</div>
							<div v-else-if="row.status == 'absent'">
								<Badge label="غياب" variant="error" />
							</div>
						</TableCell>

						<TableCell>
							<div v-if="!row.attendance_id">
								<Button
									variant="light-ghost"
									v-on:click="addSchedule(row.employee_id)"
									>اضافة دوام</Button
								>
							</div>
							<div v-else>
								{{ row?.start_time }} - {{ row?.end_time }}
							</div>
						</TableCell>

						<TableCell>
							{{ row?.sign_in }}
							<Button
								variant="light-ghost"
								v-on:click="sign_in(row.employee_id, row.attendance_id)"
							>
								تسجيل حضور</Button
							>
						</TableCell>
						<TableCell>
							{{ row?.sign_out }}
							<Button
								variant="light-ghost"
								v-on:click="sign_in(row.employee_id, row.attendance_id)"
							>
								تسجيل انصراف</Button
							>
						</TableCell>
						<TableCell>
							{{ row?.work_hours }}
						</TableCell>
						<TableCell>
							{{ row?.difference_between_work_hours }}
						</TableCell>

						<TableCell>
							<DropdownMenu>
								<DropdownMenuTrigger as-child>
									<Button variant="light-ghost" icon="i-tabler-dots" />
								</DropdownMenuTrigger>
								<DropdownMenuContent>
									<DropdownMenuItem
										label="حذف"
										icon="i-tabler-trash"
										@click="
											RemoveSchedule(
												row.attendance_id,
												row.employee_id
											)
										"
									/>
									<DropdownMenuItem
										label="يوم راحة"
										icon="i-tabler-pencil"
										:ui="{ icon: 'text-red-500' }"
										@click="RestDay(row.attendance_id)"
									/>
								</DropdownMenuContent>
							</DropdownMenu>
						</TableCell>
					</template>
				</DataTable>
			</div>
		</div>
	</div>
</template>

<style></style>
