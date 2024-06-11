export {};

declare global {
	interface JobLevel extends BaseModel {
		name: string;
		desc: string;
		is_active: boolean;
		translations: ModelTranslations<{ name: string; desc: string }>;
	}
}
