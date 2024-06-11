import defaultTheme from "tailwindcss/defaultTheme";
import colors from "tailwindcss/colors";
import forms from "@tailwindcss/forms";
import containerQueries from "@tailwindcss/container-queries";
import typography from "@tailwindcss/typography";
import animate from "tailwindcss-animate";
import radix from "tailwindcss-radix";
import { gridAutoFit, icons, appPlugin } from "./resources/js/plugins/tailwind";

const getColorCssVariables = (colorName) => {
	let shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
	return shades.reduce((obj, shade) => {
		return (obj = {
			...obj,
			[shade]: `hsl(var(--${colorName}-${shade}) / <alpha-value>)`,
		});
	}, {});
};

const getRadixScaleCssVariables = (colorName) => {
	let scales = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
	let alphaScales = [
		"a1",
		"a2",
		"a3",
		"a4",
		"a5",
		"a6",
		"a7",
		"a8",
		"a9",
		"a10",
		"a11",
		"a12",
	];
	let scaleAliases = {
		1: ["base"],
		2: ["bg-subtle"],
		3: ["bg"],
		4: ["bg-hover"],
		5: ["bg-active"],
		6: ["line"],
		7: ["border"],
		8: ["border-hover", "focus", "text-placeholder"],
		9: ["solid", "DEFAULT"],
		10: ["solid-hover"],
		11: ["text"],
		12: ["text-contrast"],
	};
	const getAliases = (colorName, scale) => {
		return scaleAliases[scale].reduce((obj, alias, index) => {
			return (obj = {
				...obj,
				[alias]: `var(--${colorName}-${scale})`,
			});
		}, {});
	};
	let result = scales.reduce((obj, scale, index) => {
		return (obj = {
			...obj,
			[scale]: `var(--${colorName}-${scale})`,
			[alphaScales[index]]: `var(--${colorName}-${alphaScales[index]})`,
			...getAliases(colorName, scale),
		});
	}, {});

	const additionalTokens = ["contrast", "surface", "track", "indicator"];
	return {
		...result,
		...additionalTokens.reduce((obj, token) => {
			return (obj = {
				...obj,
				[token]: `var(--${colorName}-${token})`,
			});
		}, {}),
	};
};

/** @type {import('tailwindcss').Config} */
export default {
	content: [
		"./resources/**/*.blade.php",
		"./resources/**/*.{js,ts,tsx,vue}",
		"./resources/**/*.vue",
		"Modules/*/resources/**/*.{js,ts,tsx,vue,blade.php}",
	],
	darkMode: "class",
	theme: {
		extend: {
			fontFamily: {
				arabic: "",
				english: "",
				sans: [
					"Noto Kufi Arabic",
					"Tajawal",
					...defaultTheme.fontFamily.sans,
				],
			},
			fontSize: {
				tiny: "0.625rem",
			},
			colors: {
				// todo: remove this color (blue) after refactor
				blue: {
					50: "#f0f7ff",
					100: "#dfeeff",
					200: "#b9defe",
					300: "#7bc4fe",
					400: "#34a6fc",
					500: "#0a8ced",
					600: "#006dcb",
					700: "#00529c", //blue accent color
					800: "#054a87",
					900: "#0a3e70",
					950: "#07274a",
				},
				background: "var(--color-background)",
				overlay: "var(--color-overlay)",
				panel: {
					solid: "var(--color-panel-solid)",
					translucent: "var(--color-panel-translucent)",
				},
				surface: "var(--color-surface)",
				// accent color is dynamic color changes with data-accent-color="<color-name>" used for implementing components variants
				accent: {
					DEFAULT: "hsl(var(--accent))",
					foreground: "hsl(var(--accent-foreground))",
					...getRadixScaleCssVariables("accent"),
				},
				gray: {
					...getRadixScaleCssVariables("gray"),
				},
				primary: {
					DEFAULT: "hsl(var(--primary) / <alpha-value>)",
					foreground: "hsl(var(--primary-foreground) / <alpha-value>)",
					...getColorCssVariables("primary"),
					...getRadixScaleCssVariables("primary"),
				},
				secondary: {
					// DEFAULT: "hsl(var(--secondary))",
					DEFAULT: colors.slate[500],
					foreground: "hsl(var(--secondary-foreground))",
					// ...getColorCssVariables("secondary"),
					...colors.slate,
					...getRadixScaleCssVariables("secondary"),
				},
				red: {
					...getRadixScaleCssVariables("red"),
					50: "#fef2f2",
					100: "#fee3e2",
					200: "#fecbca",
					300: "#fba8a6",
					400: "#f77572",
					500: "#ee4945",
					600: "#dd3834", //red accent color
					700: "#b8211d",
					800: "#981f1c",
					900: "#7e201e",
					950: "#450b0a",
				},
				green: {
					...getRadixScaleCssVariables("green"),
					50: "#f2fdf0",
					100: "#e1fbdd",
					200: "#c4f6bc",
					300: "#93ed88",
					400: "#5cdb4d",
					500: "#36c225",
					600: "#29a71a",
					700: "#217e17",
					800: "#206318",
					900: "#1a5215",
					950: "#092d06",
				},
				yellow: {
					...getRadixScaleCssVariables("yellow"),
					...colors.yellow,
				},

				// todo: shadCn tokens remove it after refactor
				border: "var(--border)",
				input: "hsl(var(--input))",
				ring: "hsl(var(--ring))",
				foreground: "hsl(var(--foreground))",

				destructive: {
					DEFAULT: "hsl(var(--destructive))",
					foreground: "hsl(var(--destructive-foreground))",
				},
				muted: {
					DEFAULT: "hsl(var(--muted))",
					foreground: "hsl(var(--muted-foreground))",
				},

				popover: {
					DEFAULT: "hsl(var(--popover))",
					foreground: "hsl(var(--popover-foreground))",
				},
				card: {
					DEFAULT: "hsl(var(--card))",
					foreground: "hsl(var(--card-foreground))",
				},
			},
			borderRadius: {
				xl: "calc(var(--radius) + 4px)",
				lg: "var(--radius)",
				md: "calc(var(--radius) - 2px)",
				DEFAULT: "calc(var(--radius) - 4px)",
				sm: "calc(var(--radius) - 6px)",
			},
			keyframes: {
				"accordion-down": {
					from: { height: 0 },
					to: { height: "var(--radix-accordion-content-height)" },
				},
				"accordion-up": {
					from: { height: "var(--radix-accordion-content-height)" },
					to: { height: 0 },
				},
				"collapsible-down": {
					from: { height: 0 },
					to: { height: "var(--radix-collapsible-content-height)" },
				},
				"collapsible-up": {
					from: { height: "var(--radix-collapsible-content-height)" },
					to: { height: 0 },
				},
			},
			animation: {
				"accordion-down": "accordion-down 0.2s ease-out",
				"accordion-up": "accordion-up 0.2s ease-out",
				"collapsible-down": "collapsible-down 0.2s ease-in-out",
				"collapsible-up": "collapsible-up 0.2s ease-in-out",
			},
		},
		container: {
			center: true,
			padding: "1.5rem",
		},
	},
	plugins: [
		forms({ strategy: "class" }),
		containerQueries,
		typography,
		appPlugin,
		gridAutoFit,
		icons,
		animate,
		radix,
	],
};
