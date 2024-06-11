<script setup lang="ts">
import dayjs from "dayjs";
const props = defineProps<{
	employees?: PaginatedData<Employee>;

	shifts: {
		name: string;
		id: number | string;
	}[];
}>();
console.log(props.employees);
const breadcrumbs: BreadCrumbs = [
	{
		title: "جدول الدوامات",
		href: route("shifts.employees"),
	},
];
const formId = "shift-employees-form";
const form = useForm({
	employee_id: undefined,
	shift_id: undefined,
	day_date: null,

	started_at: undefined,
	ended_at: undefined,
});

const employeeToManage = ref();
const editAttendance = (employee) => {
	showForm.value = true;
	employeeToManage.value = employee;
};

const removeAttendance = (attendance_id) => {
	axios
		.post(route(`attendance.restDay`, { attendance: attendance_id }), {})
		.then((response) => {
			console.log("Attendance marked as rest day:", response.data);
		})
		.catch((error) => {
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
const formateDate = (date, format?: string = "DD dddd MMMM YYYY") => {
	return dayjs(date).format(format);
};
const showForm = ref(false);

// calendar logic
const selectedCalendarMode = ref();
const calendarModes = [
	{ label: "إسبوعي", value: "weekly" },
	{ label: "يومي", value: "daily" },
];

const weekModelValue = ref();

const startOfWeek = computed(() => {
	return weekModelValue.value
		? dayjs(weekModelValue.value?.at(0))
		: dayjs().startOf("week");
});

const weekDaysDates = computed(() => {
	const start = startOfWeek;

	return Array.from({ length: 7 }).map((d, i) =>
		dayjs(startOfWeek.value).add(i, "day")
	);
});

const headers = computed(() => {
	const selectedWeekDaysFormatted = weekDaysDates.value.map((day) => {
		return dayjs(day).format("dddd DD ");
	});
	return [
		{ label: "اسم الموظف", props: { class: "sticky start-0 min-w-60" } },
		...selectedWeekDaysFormatted,
	];
});

const selectNextWeek = () => {
	weekModelValue.value = [dayjs(startOfWeek.value).add(1, "week")];
};
const selectPrevWeek = () => {
	weekModelValue.value = [dayjs(startOfWeek.value).subtract(1, "week")];
};

watch(
	() => startOfWeek.value,
	() => {
		router.reload({
			data: { start_date: startOfWeek.value?.toISOString() },
		});
	}
);
</script>

<template>
	<div>
		<Header title="جدول الدوامات" :breadcrumbs>
			<template #actions>
				<DropdownMenu>
					<DropdownMenuTrigger as-child>
						<Button variant="white" icon="i-tabler-dots" />
					</DropdownMenuTrigger>
					<DropdownMenuContent>
						<DropdownMenuItem
							label="إلغاء النشر"
							icon="i-tabler-circle-x "
						/>
						<DropdownMenuItem
							label="مسح هذا الاسبوع"
							icon="i-tabler-eraser-off "
						/>
					</DropdownMenuContent>
				</DropdownMenu>
				<Button
					variant="error"
					label="فشل التحميل"
					icon="i-tabler-alert-circle"
				/>
				<HrEmployeeShiftsModelForm
					v-model:open="showForm"
					:employee="employeeToManage"
					@close="employeeToManage = undefined"
				/>
				<Button variant="light" label="نشر" icon="i-tabler-share-3" />
			</template>
		</Header>
		<div class="page-content">
			<div class="panel flex flex-col gap-6">
				<CardTitle class="text-3xl">
					<span class="i-tabler-calendar" />
					{{ formateDate(startOfWeek) }}
				</CardTitle>

				<div class="flex w-fit items-center justify-start gap-4">
					<div class="flex w-fit items-center justify-start gap-2">
						<Button
							variant="light"
							size="lg"
							icon="i-tabler-arrow-right"
							@click="selectPrevWeek"
							class="rounded-e-sm"
						/>
						<DateInput
							class="!w-auto [&>div]:inline-block"
							week-picker
							v-model="weekModelValue"
							auto-apply
						>
							<template #trigger>
								<Button
									variant="light"
									size="lg"
									icon="i-tabler-calendar"
									class="grow-0 rounded-sm"
								/>
							</template>
						</DateInput>
						<Button
							variant="light"
							size="lg"
							icon="i-tabler-arrow-left"
							@click="selectNextWeek"
							class="rounded-s-none"
						/>
					</div>

					<Select
						v-model="selectedCalendarMode"
						:options="calendarModes"
						option-label="label"
						option-value="value"
						class="min-w-40"
					/>
				</div>
			</div>
			<div class="panel">
				<DataTable :headers :data="employees">
					<template #row="{ row }">
						<TableCell class="sticky start-0 bg-white shadow-sm">
							<div class="flex items-center gap-2">
								<Avatar />
								<div class="flex flex-col leading-none">
									<span class="font-medium capitalize text-blue-700">{{
										row.name
									}}</span>
								</div>
							</div>
							<div class="ms-2 mt-1 inline-flex items-center gap-1">
								<span class="i-tabler-clock" />
								<span class="text-xs leading-none"
									>{{ row?.total_multiplied_hours }} ساعة
								</span>
							</div>
						</TableCell>
						<TableCell
							v-for="attendance in row?.attendances"
							:key="attendance.id"
						>
							<div
								v-if="attendance"
								:class="[
									{
										'border-green-300 bg-green-100':
											attendance.status === 'attended',
										'border-red-300 bg-red-100':
											attendance.status === 'absent',
										'border-blue-300 bg-blue-100':
											!!!attendance.status,
									},
									' flex flex-col  gap-2 rounded-lg border-s-8 p-4 px-6 ',
								]"
							>
								<div>
									{{ attendance?.start_time }} -
									{{ attendance?.end_time }}
								</div>
								<div class="flex items-center justify-between gap-2">
									<span>{{ row.shifts.shift.name }}</span>
									<DropdownMenu>
										<DropdownMenuTrigger as-child>
											<Button
												variant="light-ghost"
												icon="i-tabler-dots"
											/>
										</DropdownMenuTrigger>
										<DropdownMenuContent
											v-if="attendance?.start_time"
										>
											<DropdownMenuLabel label="الإجراءات" />
											<DropdownMenuItem
												label="تعديل الدوام"
												icon="i-tabler-pencil "
												@select="editAttendance(row)"
											/>
											<DropdownMenuItem
												label="إلغاء النشر"
												icon="i-tabler-circle-x "
											/>
											<AlertDialog
												title="حذف الدوام"
												description="هل أنت متأكد من حذف هذا الدوام؟"
												@confirm="
													() => removeAttendance(attendance)
												"
												:loading="false"
											>
												<DropdownMenuItem
													@select.prevent=""
													label="حذف الدوام"
													icon="i-tabler-trash"
													:loading="false"
													:ui="{
														icon: 'text-red-500',
													}"
												/>
											</AlertDialog>
										</DropdownMenuContent>
									</DropdownMenu>
								</div>
							</div>

							<Button
								v-else
								class="invisible grid h-full place-items-center rounded-xl p-4 group-hover/cell:visible"
								@click="editAttendance(row)"
							>
								<span class="i-tabler-plus size-6" />
							</Button>
						</TableCell>
					</template>
				</DataTable>
			</div>
		</div>
	</div>
</template>

<style></style>
