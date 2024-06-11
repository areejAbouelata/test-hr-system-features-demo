import type { SidebarItems } from "@/Layouts/Partials/Sidebar";

function hrNavigation(): SidebarItems {
	return [
		{
			type: "group",
			label: trans("human-resource"),
			items: [
				{
					label: trans("employees"),
					href: route("employees.index"),
					active: route().current("employees.*"),
					icon: "i-tabler-users",
				},
			],
		},
	];
}

function hrSettingsNavigation(): SidebarItems {
	return [
		{
			type: "menu",
			icon: "i-tabler-users-group",
			label: trans("employees"),
			items: [
				{
					label: trans("departments"),
					href: route("departments.index"),
					active: route().current("departments.*"),
					icon: "i-tabler-list-details",
				},
				{
					label: trans("job-levels"),
					href: route("job-level.index"),
					active: route().current("job-level.*"),
					icon: "i-tabler-list-details",
				},
				{
					label: trans("job-titles"),
					href: route("job-title.index"),
					active: route().current("job-title.*"),
					icon: "i-tabler-list-details",
				},
				{
					label: trans("employee-documents"),
					href: route("employee-document.index"),
					active: route().current("employee-document.*"),
					icon: "i-tabler-list-details",
				},
				{
					label: trans("allowances"),
					href: route("allowance.index"),
					active: route().current("allowance.*"),
					icon: "i-tabler-list-details",
				},
				{
					label: trans("contract-types"),
					href: route("contract-type.index"),
					active: route().current("contract-type.*"),
					icon: "i-tabler-list-details",
				},
			],
		},
	];
}

export { hrNavigation, hrSettingsNavigation };

// if (import.meta.hot) {
// 	import.meta.hot.accept((newModule) => {
// 		console.log("hot reloaded:", newModule);
// 		if (newModule) {
// 			// newModule is undefined when SyntaxError happened
// 			import.meta?.hot?.invalidate();
// 			import.meta?.hot?.prune(() => {});
// 			console.log("updated: count is now ", newModule.hrNavigation().length);
// 		}
// 	});
// }
