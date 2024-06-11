export {};

declare global {
	export interface JobLevelTranslation {
		name: string;
		desc: string;
	}
	interface JobLevel extends BaseModel, JobLevelTranslation {
		translations: ModelTranslations<JobLevelTranslation>;
	}
	export type JobLevels = JobLevel[];

	export interface JobTitleTranslation {
		title: string;
		desc: string;
	}
	interface JobTitle extends BaseModel, JobTitleTranslation {
		translations: ModelTranslations<JobTitleTranslation>;
	}
	export type JobTitles = JobTitle[];

	export interface ContractTypeTranslation {
		name: string;
	}
	interface ContractType extends BaseModel, ContractTypeTranslation {
		translations: ModelTranslations<ContractTypeTranslation>;
	}
	export type ContractTypes = ContractType[];

	export interface AllowanceTranslation {
		name: string;
		desc: string;
	}
	interface Allowance extends BaseModel, AllowanceTranslation {
		translations: ModelTranslations<AllowanceTranslation>;
	}
	export type Allowances = Allowance[];

	export interface DepartmentTranslation {
		name: string;
		short_name: string;
		description: string;
	}
	interface Department extends BaseModel, DepartmentTranslation {
		translations: ModelTranslations<DepartmentTranslation>;
		head_of_department: Employee | Employee["id"];
		parent_department_id: number;
	}
	export type Departments = Department[];
}
