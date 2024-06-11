import { defineConfig } from "vite";
import path from "path";
import fs from "node:fs";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { watch } from "vite-plugin-watch";

function getUIComponentFolders() {
	const uiDirectory = path.resolve("resources/js/Components/ui"); // Change this to your UI components directory path
	const folders = fs
		.readdirSync(uiDirectory, { withFileTypes: true })
		.filter((dirent) => dirent.isDirectory())
		.map((dirent) => dirent.name);

	// console.log(folders);
	return folders;
}
const uiComponents = getUIComponentFolders();

const moduleResourcePath = (module) => `Modules/${module}/resources/js`;
export default defineConfig({
	plugins: [
		watch({
			pattern: ["routes/*.php", "Modules/*/routes/*.php"],
			command: "php artisan ziggy:generate --types",
		}),
		laravel({
			input: ["resources/js/app.js"],
			refresh: true,
		}),
		vue({
			template: {
				transformAssetUrls: {
					base: null,
					includeAbsolute: false,
				},
			},
		}),
		AutoImport({
			dirs: [
				"./resources/js/Composables",
				"./resources/js/utils",
				// "./resources/js/Stores",
			],
			vueTemplate: true,
			imports: [
				"vue",

				{
					"@inertiajs/vue3": ["useForm", "usePage", "router"],
				},
				{
					"class-variance-authority": ["cva"],
				},
				{
					uuid: [["v4", "uuid"]],
				},
				{
					"laravel-vue-i18n": ["trans", "wTrans"],
				},
				{
					"change-case": ["sentenceCase"],
				},
				{
					dayjs: ["default"],
				},
				{
					"radix-vue": [
						"useForwardPropsEmits",
						"useForwardProps",
						"useEmitAsProps",
					],
				},
			],
			dts: true,
		}),
		Components({
			dts: "./auto-components.d.ts",
			resolvers: [
				{
					resolve: (componentName) => {
						if (["Link", "Head"].includes(componentName)) {
							return {
								name: componentName,
								from: "@inertiajs/vue3",
							};
						}
						if (["DatePicker"].includes(componentName)) {
							return {
								name: "default",
								as: "DatePicker",
								from: "@vuepic/vue-datepicker",
							};
						}
						if (
							["VisuallyHidden", "Slot", "Primitive"].includes(
								componentName
							)
						) {
							return {
								name: componentName,
								from: "radix-vue",
							};
						}
					},
					type: "component",
				},
			],

			directoryAsNamespace: true,
			collapseSamePrefixes: true,
			globalNamespaces: [
				"Partials",
				"partials",
				"resources",
				"js",
				"Layouts",
				"layouts",
				"Pages",
				"Components",
				"ui",
				"app",
				...uiComponents,
			],
			dirs: ["./resources/js/", "Modules/"],
		}),
	],
	resolve: {
		alias: {
			"@finance": moduleResourcePath("Finance"),
			"@hr": moduleResourcePath("Hr"),
			"ziggy-js": path.resolve("vendor/tightenco/ziggy"),
		},
	},
});
