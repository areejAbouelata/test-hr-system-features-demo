<script setup lang="ts">
const props = defineProps<{
	balances?: PaginatedData<{
		id: number | string;
		user_id: number | string;
		user_name: string;
		annual_entitlement: number;
		opening_balance: number;
		carry_forward_balance: number;
		used_leaves_this_year: number;
		booked_balance: number;
		manual_entry: number;
		up_to_date_balance: number;
		year_end_balance: number;
		created_at: string;
		updated_at: string;
		updated_by: string;
	}>;

	numbers?: {
		value: string;
		id: number | string;
	};
}>();

const breadcrumbs: BreadCrumbs = [
	{
		title: "جدول الدوامات",
		href: route("shifts.employees"),
	},
];

const formId = "annual-form";
const form = useForm({
	balance_id: undefined,
	annual: undefined,
});

const updateformId = "manual-form";
const updateForm = useForm({
	balance_id: undefined,
	manual: undefined,
});

const editAnnual = (balance: balance): void => {
	showAnnualForm.value = true;

	form.balance_id = balance.id;
	form.annual = balance.annual_entitlement;
};

const showAnnualForm = ref(false);
const submit = () => {
	form.put(route("leave_balances.updateAnnual", form.balance_id), {
		onSuccess: () => {
			showAnnualForm.value = false;
		},
	});
};

const editManual = (balance): void => {
	showManualForm.value = true;
	updateForm.balance_id = balance.id;
	updateForm.manual = balance.manual_entry;
};
const showOther = (id): void => {
	showManualForm.value = true;

	/*  updateForm.balance_id = balance.id;
    updateForm.manual = balance.manual_entry; */
};

const showManualForm = ref(false);
const update = () => {
	updateForm.put(route("leave_balances.updateManual", updateForm.balance_id)),
		{
			onSuccess: () => {
				showManualForm.value = false;
			},
		};
};

const headers = [
	"اسم الموظف",
	"الاستحقاق السنوي للإجازة",
	"الرصيد الافتتاحي",
	"الرصيد المرحل من السنة السابقة",
	"الإجازات المستخدمة في السنة الحالية",
	"الرصيد المحجوز",
	"الإدخال اليدوي",
	"الرصيد المستحق إلى هذا اليوم",
	"رصيد نهاية السنة الحالية",
];

const statusTypes = [
	{
		id: "inactive",
		name: "غير مستخدمة",
	},
	{
		id: "active",
		name: "مستخدمة",
	},
];
</script>

<template>
	<div>
		<Header title="جدول العهود" :breadcrumbs>
			<template #actions>
				<Sheet v-model:open="showAnnualForm" title="إضافة سلعة">
					<!--  <template #trigger>
                        <Button variant="success" label="إضافة عهدة" icon="i-tabler-plus" />
                    </template> -->
					<div class="sheet-body">
						<div class="panel">
							<Form :id="formId" @submit="submit">
								<FormField
									label=""
									:error="updateForm.errors.balance_id"
								>
									<Input v-model="form.balance_id" type="hidden" />
								</FormField>
								<FormField
									label="اختر عدد ايام الاجازة"
									:error="form.errors.annual"
								>
									<Combobox
										v-model="form.annual"
										:multiple="false"
										:options="numbers"
										option-label="name"
										option-value="id"
									/>
								</FormField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="annual-form"
							type="submit"
							label="حفظ"
							:loading="form.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>

				<Sheet v-model:open="showManualForm" title="تعديل سلعة">
					<div class="sheet-body">
						<div class="panel">
							<Form :id="updateformId" @submit="update">
								<FormField
									label=""
									:error="updateForm.errors.balance_id"
								>
									<Input
										v-model="updateForm.balance_id"
										type="hidden"
									/>
								</FormField>
								<FormField
									label="ادخل الرقم"
									:error="updateForm.errors.balance_id"
								>
									<Input v-model="updateForm.manual" type="number" />
								</FormField>
							</Form>
						</div>
					</div>
					<template #footer>
						<Button
							form="manual-form"
							type="submit"
							label="حفظ"
							:loading="updateForm.processing"
						/>
						<SheetClose />
					</template>
				</Sheet>
			</template>
		</Header>

		<div class="page-content">
			<div class="panel">
				<DataTable :headers :data="balances">
					<template #row="{ row }">
						<TableCell>
							{{ row?.user_name }}
						</TableCell>
						<TableCell>
							{{ row?.annual_entitlement }}
							<Button @click="editAnnual(row)" variant="light-ghost"
								>تعديل
							</Button>
						</TableCell>

						<TableCell>
							{{ row?.opening_balance }}
						</TableCell>

						<TableCell>
							{{ row?.carry_forward_balance }}
						</TableCell>
						<TableCell>
							{{ row?.used_leaves_this_year }}
						</TableCell>
						<TableCell>
							{{ row?.booked_balance }}
						</TableCell>
						<TableCell>
							{{ row?.manual_entry }}
							<Button @click="editManual(row)" variant="light-ghost"
								>تعديل
							</Button>
						</TableCell>

						<TableCell>
							{{ row?.up_to_date_balance }}
						</TableCell>
						<TableCell>
							{{ row?.year_end_balance }}
						</TableCell>

						<TableCell>
							{{ row?.updated_at }} {{ row?.updated_by }}
						</TableCell>

						<!--  <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="light-ghost"  icon="i-tabler-dots" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem label="تعديل" icon="i-tabler-pencil" @click="showOther(row.user_id)" />

                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell> -->
					</template>
				</DataTable>
			</div>
		</div>
	</div>
</template>

<style></style>
