export {};

declare global {
	interface User extends BaseModel {
		name: string;
		email: string;
		password: string;
		note: string;
		is_ban: boolean;
		birth_date: Date;
		gender: string;
		country_id: Country["id"];
		branch_id: Branch["id"];
		// iqama data
		civilization_state: string;
		employing_number: string;
		id_number: string;
		nationality: string;
		iqama_expiration_date: Date;
		passport: string;
		attachments: File[];
		attachment_name: string[];
		address: string;
		//salary data
		salary: number;
		allowances: EmployeeAllowance[];
		shift_id: Shift["id"];
		//job data
		job_title_id: JobTitle["id"];
		job_level_id: JobLevel["id"];
		department_id: Department["id"];
		manager_id: User["id"];
		start_date: Date;
	}
	interface Employee extends User {
		name: string;
		translations: Translation[];
	}
	export type Employees = Employees;

	interface EmployeeDocument extends BaseModel {
		name: string;
		is_active: boolean;
		expiration_date: Date;
		translations: ModelTranslations<{ name: string }>;
	}

	interface EmployeeAllowance extends BaseModel {
		user_id: User["id"];
		allowance_id: Allowance["id"];
		price: number;
	}
	export type EmployeeAllowances = EmployeeAllowance[];

	interface EmployeeAttachment extends BaseModel {
		employee_id: Employee["id"];
		attachment: string;
		attachment_name: string;
	}
	export type EmployeeAttachments = EmployeeAttachment[];
}
