<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MercaTodo - Email Delivering</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */*,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}::after,::before{--tw-content:''}html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:Figtree, sans-serif;font-feature-settings:normal}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;font-weight:inherit;line-height:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dd,dl,figure,h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}menu,ol,ul{list-style:none;margin:0;padding:0}textarea{resize:vertical}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}[role=button],button{cursor:pointer}:disabled{cursor:default}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]{display:none}*, ::before, ::after{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::-webkit-backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }.relative{position:relative}.mx-auto{margin-left:auto;margin-right:auto}.mx-6{margin-left:1.5rem;margin-right:1.5rem}.ml-4{margin-left:1rem}.mt-16{margin-top:4rem}.mt-6{margin-top:1.5rem}.mt-4{margin-top:1rem}.-mt-px{margin-top:-1px}.mr-1{margin-right:0.25rem}.flex{display:flex}.inline-flex{display:inline-flex}.grid{display:grid}.h-16{height:4rem}.h-7{height:1.75rem}.h-6{height:1.5rem}.h-5{height:1.25rem}.min-h-screen{min-height:100vh}.w-auto{width:auto}.w-16{width:4rem}.w-7{width:1.75rem}.w-6{width:1.5rem}.w-5{width:1.25rem}.max-w-7xl{max-width:80rem}.shrink-0{flex-shrink:0}.scale-100{--tw-scale-x:1;--tw-scale-y:1;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.grid-cols-1{grid-template-columns:repeat(1, minmax(0, 1fr))}.items-center{align-items:center}.justify-center{justify-content:center}.gap-6{gap:1.5rem}.gap-4{gap:1rem}.self-center{align-self:center}.rounded-lg{border-radius:0.5rem}.rounded-full{border-radius:9999px}.bg-gray-100{--tw-bg-opacity:1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.bg-white{--tw-bg-opacity:1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-red-50{--tw-bg-opacity:1;background-color:rgb(254 242 242 / var(--tw-bg-opacity))}.bg-dots-darker{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")}.from-gray-700\/50{--tw-gradient-from:rgb(55 65 81 / 0.5);--tw-gradient-to:rgb(55 65 81 / 0);--tw-gradient-stops:var(--tw-gradient-from), var(--tw-gradient-to)}.via-transparent{--tw-gradient-to:rgb(0 0 0 / 0);--tw-gradient-stops:var(--tw-gradient-from), transparent, var(--tw-gradient-to)}.bg-center{background-position:center}.stroke-red-500{stroke:#ef4444}.stroke-gray-400{stroke:#9ca3af}.p-6{padding:1.5rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.text-center{text-align:center}.text-right{text-align:right}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-sm{font-size:0.875rem;line-height:1.25rem}.font-semibold{font-weight:600}.leading-relaxed{line-height:1.625}.text-gray-600{--tw-text-opacity:1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-900{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.text-gray-500{--tw-text-opacity:1;color:rgb(107 114 128 / var(--tw-text-opacity))}.underline{-webkit-text-decoration-line:underline;text-decoration-line:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.shadow-2xl{--tw-shadow:0 25px 50px -12px rgb(0 0 0 / 0.25);--tw-shadow-colored:0 25px 50px -12px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.shadow-gray-500\/20{--tw-shadow-color:rgb(107 114 128 / 0.2);--tw-shadow:var(--tw-shadow-colored)}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.selection\:bg-red-500 *::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white *::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.selection\:bg-red-500::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.hover\:text-gray-900:hover{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.hover\:text-gray-700:hover{--tw-text-opacity:1;color:rgb(55 65 81 / var(--tw-text-opacity))}.focus\:rounded-sm:focus{border-radius:0.125rem}.focus\:outline:focus{outline-style:solid}.focus\:outline-2:focus{outline-width:2px}.focus\:outline-red-500:focus{outline-color:#ef4444}.group:hover .group-hover\:stroke-gray-600{stroke:#4b5563}@media (prefers-reduced-motion: no-preference){.motion-safe\:hover\:scale-\[1\.01\]:hover{--tw-scale-x:1.01;--tw-scale-y:1.01;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}}@media (prefers-color-scheme: dark){.dark\:bg-gray-900{--tw-bg-opacity:1;background-color:rgb(17 24 39 / var(--tw-bg-opacity))}.dark\:bg-gray-800\/50{background-color:rgb(31 41 55 / 0.5)}.dark\:bg-red-800\/20{background-color:rgb(153 27 27 / 0.2)}.dark\:bg-dots-lighter{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")}.dark\:bg-gradient-to-bl{background-image:linear-gradient(to bottom left, var(--tw-gradient-stops))}.dark\:stroke-gray-600{stroke:#4b5563}.dark\:text-gray-400{--tw-text-opacity:1;color:rgb(156 163 175 / var(--tw-text-opacity))}.dark\:text-white{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.dark\:shadow-none{--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.dark\:ring-1{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)}.dark\:ring-inset{--tw-ring-inset:inset}.dark\:ring-white\/5{--tw-ring-color:rgb(255 255 255 / 0.05)}.dark\:hover\:text-white:hover{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.group:hover .dark\:group-hover\:stroke-gray-400{stroke:#9ca3af}}@media (min-width: 640px){.sm\:fixed{position:fixed}.sm\:top-0{top:0px}.sm\:right-0{right:0px}.sm\:ml-0{margin-left:0px}.sm\:flex{display:flex}.sm\:items-center{align-items:center}.sm\:justify-center{justify-content:center}.sm\:justify-between{justify-content:space-between}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width: 768px){.md\:grid-cols-2{grid-template-columns:repeat(2, minmax(0, 1fr))}}@media (min-width: 1024px){.lg\:gap-8{gap:2rem}.lg\:p-8{padding:2rem}}
        </style>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                        y="0px" width="50px" height="50px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000"
                        xml:space="preserve">
                        <circle fill-rule="evenodd" clip-rule="evenodd" fill="#FF0000" cx="500" cy="500.851" r="491.074" />
                        <circle fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="#FFFFFF" stroke-width="16"
                            stroke-miterlimit="10" cx="500" cy="500.851" r="322.43" />
                        <circle fill-rule="evenodd" clip-rule="evenodd" fill="none" cx="500" cy="501.772" r="472.69" />
                        <text transform="matrix(0.5276 0.8495 -0.8495 0.5276 60.77 690.6162)">
                            <tspan x="0" y="0" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641">M</tspan>
                            <tspan x="162.315" y="-6.763" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-16.435">E</tspan>
                            <tspan x="264.236" y="-38.317" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-29.892">R</tspan>
                            <tspan x="365.102" y="-98.912" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-44.223">C</tspan>
                            <tspan x="449.23" y="-183.568" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-59.187">A</tspan>
                            <tspan x="511.786" y="-294.908" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-73.526">T</tspan>
                            <tspan x="542.683" y="-402.091" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-88.769">O</tspan>
                            <tspan x="542.25" y="-543.867" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-105.605">D</tspan>
                            <tspan x="502.202" y="-676.359" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-122.494">O</tspan>
                        </text>
                        <circle fill-rule="evenodd" clip-rule="evenodd" fill="none" cx="499.941" cy="503.14" r="472.69" />
                        <text transform="matrix(-0.508 -0.8614 0.8614 -0.508 943.3965 324.4189)">
                            <tspan x="0" y="0" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641">M</tspan>
                            <tspan x="162.329" y="-6.772" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-16.445">E</tspan>
                            <tspan x="264.231" y="-38.34" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-29.89">R</tspan>
                            <tspan x="365.084" y="-98.912" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-44.211">C</tspan>
                            <tspan x="449.058" y="-184.51" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-58.541">A</tspan>
                            <tspan x="511.76" y="-294.909" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-73.514">T</tspan>
                            <tspan x="542.69" y="-402.116" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-88.769">O</tspan>
                            <tspan x="542.25" y="-543.896" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-105.615">D</tspan>
                            <tspan x="502.198" y="-676.337" fill="#FFFFFF" font-family="'MyriadPro-Bold'" font-size="167.5641"
                                rotate="-122.485">O</tspan>
                        </text>
                        <g>
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M691.07,710.288c-128.715,0-257.427,0-386.14,0c-3.302-2.297-4.352-5.411-4.186-9.424c0.282-6.829,0.074-13.677,0.079-20.521
                        c0.003-7.61,1.829-9.592,9.353-9.424c2.873,0.064,3.886-0.396,3.881-3.67c-0.118-70.865-0.097-141.729-0.048-212.596
                        c0.001-2.046-0.423-3.645-1.882-5.13c-3.63-3.697-5.137-8.071-4.823-13.381c0.381-6.406,0.138-12.854,0.065-19.282
                        c-0.025-2.222,0.278-4.334,0.98-6.432c7.399-22.111,14.768-44.23,22.167-66.341c3.297-9.851,9.75-14.747,20.065-14.939
                        c2.391-0.044,2.9-0.757,2.871-2.995c-0.12-9.027,0.124-18.062-0.129-27.085c-0.112-4.005,0.863-7.137,4.188-9.426
                        c93.66,0,187.32,0,280.978,0c3.314,2.301,4.301,5.433,4.189,9.44c-0.253,9.031-0.003,18.073-0.133,27.109
                        c-0.033,2.301,0.585,2.91,2.917,2.952c10.309,0.187,16.761,5.094,20.057,14.977c5.267,15.797,9.962,31.808,15.908,47.341
                        c5.68,14.833,8.258,29.831,7.318,45.648c-0.267,4.505-1.144,8.396-4.221,11.55c-2.109,2.161-2.537,4.528-2.536,7.389
                        c0.058,70.106,0.079,140.213-0.05,210.318c-0.008,3.798,1.003,4.736,4.657,4.575c6.404-0.287,8.563,2.093,8.58,8.551
                        c0.016,7.118-0.198,14.247,0.079,21.357C695.416,704.865,694.361,707.98,691.07,710.288z M327.105,460.315c0,1.797,0,3.264,0,4.729
                        c-0.008,67.366-0.016,134.73-0.025,202.096c0,0.41,0.051,0.829-0.007,1.229c-0.277,1.939,0.473,2.624,2.48,2.558
                        c4.377-0.146,8.78-0.311,13.136,0.012c3.438,0.257,4.246-0.788,4.234-4.211c-0.134-39.437-0.088-78.867-0.083-118.3
                        c0-7.006,2.017-9.015,9.031-9.019c26.699-0.004,53.398-0.002,80.097,0c7.557,0,9.441,1.884,9.442,9.437
                        c0.002,39.436,0.042,78.868-0.077,118.299c-0.009,3.101,0.775,3.793,3.818,3.789c72.02-0.095,144.041-0.099,216.062,0.005
                        c3.16,0.005,3.75-0.875,3.747-3.854c-0.088-66.543-0.068-133.086-0.068-199.631c0-2.265,0-4.531,0-7.046
                        c-14.339,4.284-27.003,2.375-37.997-7.436c-2.083-1.859-2.912,0.182-4.008,1.063c-14.308,11.531-33.427,11.548-47.521-0.223
                        c-2.055-1.718-3.076-1.538-5.031,0.07c-14.23,11.704-33.479,11.735-47.45,0c-2.269-1.905-3.376-1.431-5.352,0.188
                        c-13.995,11.477-33.343,11.464-47.145-0.103c-2.15-1.801-3.26-1.704-5.351,0.016c-14.011,11.535-33.417,11.598-47.126,0.047
                        c-2.396-2.017-3.531-1.634-5.681,0.138c-11.674,9.62-27.636,11.11-40.756,4.133c-3.167-1.685-5.845-5.701-8.687-5.666
                        c-3.055,0.039-5.931,3.868-9.121,5.612C348.008,463.53,337.854,463.762,327.105,460.315z M432.054,611.659
                        c-0.019,0-0.039,0-0.057,0c0-18.465-0.043-36.931,0.048-55.396c0.012-2.497-0.378-3.526-3.261-3.51
                        c-21.749,0.134-43.499,0.126-65.247,0.007c-2.763-0.016-3.343,0.824-3.337,3.432c0.082,36.935,0.088,73.87-0.007,110.801
                        c-0.008,2.797,0.578,3.674,3.541,3.656c21.611-0.117,43.225-0.096,64.836,0.059c2.951,0.021,3.572-0.835,3.552-3.645
                        C431.993,648.596,432.054,630.127,432.054,611.659z M498.172,302.85c-42.569,0-85.14,0.044-127.708-0.085
                        c-3.327-0.011-4.047,0.959-3.924,4.055c0.24,6.012,0.292,12.054-0.015,18.061c-0.175,3.401,0.737,4.229,4.182,4.225
                        c84.591-0.12,169.184-0.107,253.774-0.116c0.823,0,1.655-0.097,2.463,0.018c2.061,0.293,2.485-0.657,2.459-2.549
                        c-0.094-6.569-0.211-13.148,0.07-19.707c0.141-3.247-0.826-3.985-4.003-3.977C583.037,302.887,540.604,302.85,498.172,302.85z
                        M497.976,684.161c-58.049,0-116.096,0-174.145,0c-10.446,0-10.396-0.004-9.749,10.51c0.108,1.748,0.517,2.434,2.335,2.342
                        c2.868-0.146,5.748-0.1,8.623-0.1c116.234-0.003,232.469-0.001,348.702-0.001c8.057,0,7.802-0.016,8.233-8.037
                        c0.22-4.094-1.071-4.816-4.924-4.807C617.36,684.201,557.668,684.161,497.976,684.161z M480.2,375.259
                        c-0.015,0-0.029,0-0.043-0.002c-0.455,9.978-0.836,19.959-1.424,29.929c-0.127,2.15,0.287,2.819,2.528,2.797
                        c11.219-0.105,22.441-0.093,33.663-0.007c2.016,0.014,2.46-0.533,2.356-2.563c-1.031-20.222-1.963-40.449-2.831-60.68
                        c-0.083-1.929-0.609-2.564-2.581-2.545c-9.169,0.093-18.338,0.104-27.504-0.005c-2.131-0.026-2.754,0.579-2.826,2.734
                        C481.199,355.034,480.663,365.146,480.2,375.259z M446.051,407.904c5.339,0,10.682-0.108,16.016,0.049
                        c2.307,0.067,3.397-0.339,3.511-2.99c0.864-19.964,1.839-39.925,2.888-59.881c0.129-2.453-0.735-2.925-2.964-2.897
                        c-8.897,0.11-17.797,0.102-26.695,0.002c-2.033-0.022-2.887,0.561-3.172,2.647c-2.741,20.05-5.49,40.101-8.468,60.116
                        c-0.444,2.978,0.645,3.028,2.866,2.985C435.37,407.834,440.712,407.904,446.051,407.904z M569.162,407.9
                        c-2.976-21.226-5.917-42.071-8.786-62.926c-0.269-1.958-0.876-2.819-3.045-2.792c-9.034,0.114-18.069,0.114-27.105,0
                        c-2.172-0.027-2.832,0.575-2.688,2.761c0.426,6.555,0.656,13.123,0.971,19.685c0.637,13.263,1.24,26.526,1.969,39.782
                        c0.074,1.33-0.774,3.521,2.083,3.51C544.72,407.873,556.885,407.9,569.162,407.9z M374.924,407.936c10.987,0,21.521,0,32.056,0
                        c6.438,0,6.426-0.002,7.32-6.582c0.35-2.574,0.793-5.135,1.164-7.706c2.301-15.971,4.54-31.949,6.941-47.904
                        c0.431-2.866-0.237-3.66-3.197-3.588c-8.621,0.208-17.251,0.167-25.874,0.01c-2.226-0.041-3.039,0.707-3.519,2.753
                        C384.931,365.79,379.969,386.641,374.924,407.936z M621.042,407.934c-0.521-2.254-0.968-4.232-1.435-6.206
                        c-4.43-18.736-8.861-37.473-13.301-56.209c-0.431-1.824-0.623-3.45-3.438-3.377c-9.032,0.234-18.071,0.153-27.105,0.038
                        c-2.081-0.026-2.556,0.554-2.238,2.542c0.929,5.806,1.705,11.635,2.531,17.456c2.017,14.214,4.005,28.434,6.079,42.642
                        c0.206,1.411-0.122,3.217,2.577,3.182C596.748,407.846,608.786,407.934,621.042,407.934z M672.532,407.904
                        c-0.132-0.777-0.146-1.189-0.273-1.564c-6.538-19.7-13.09-39.396-19.632-59.095c-1.138-3.432-3.517-5.032-7.102-5.021
                        c-7.804,0.024-15.608,0.101-23.409-0.048c-2.458-0.048-3.041,0.631-2.471,2.975c4.859,20.046,9.685,40.1,14.413,60.177
                        c0.46,1.958,1.16,2.636,3.194,2.62C648.886,407.856,660.517,407.904,672.532,407.904z M376.918,342.231
                        c-8.614,0-16.806-0.008-24.997,0.002c-5.785,0.007-7.298,1.119-9.166,6.716c-6.169,18.498-12.338,36.996-18.482,55.502
                        c-0.354,1.068-1.123,2.125-0.533,3.452c11.265,0,22.473-0.112,33.676,0.074c2.962,0.048,4.216-0.753,4.876-3.731
                        c2.599-11.719,5.482-23.375,8.251-35.057C372.648,360.32,374.74,351.446,376.918,342.231z M340.256,421.22c0-0.026,0-0.051,0-0.078
                        c-4.926,0-9.883,0.351-14.767-0.104c-4.331-0.403-5.499,1.163-4.95,5.141c0.277,2.015,0.333,4.146-0.002,6.144
                        c-1.22,7.266,2.917,11.116,8.576,14.17c5.315,2.87,10.975,3.432,16.716,2.13c9.902-2.245,14.024-7.656,14.024-17.773
                        c0-2.327-0.151-4.665,0.041-6.976c0.188-2.258-0.729-2.745-2.806-2.697C351.479,421.303,345.868,421.22,340.256,421.22z
                        M392.884,421.126c-2.733,0-5.469-0.095-8.197,0.022c-3.772,0.161-8.796-1.645-10.948,0.749c-1.855,2.063-0.131,7.058-0.668,10.654
                        c-1.061,7.111,2.983,10.874,8.357,13.918c5.358,3.036,11.155,3.641,17.057,2.281c9.856-2.273,14.042-7.704,14.042-17.738
                        c0-2.323-0.145-4.658,0.041-6.966c0.183-2.268-0.492-3.054-2.879-2.978C404.092,421.248,398.486,421.126,392.884,421.126z
                        M445.362,421.22c-5.599,0-11.199,0.087-16.795-0.044c-2.101-0.049-2.932,0.485-2.785,2.711c0.188,2.856,0.425,5.794-0.029,8.589
                        c-1.16,7.135,3.02,10.841,8.354,13.908c5.22,3.002,10.907,3.517,16.655,2.3c9.832-2.08,14.251-7.69,14.251-17.542
                        c0-2.321-0.137-4.652,0.038-6.961c0.171-2.254-0.497-3.088-2.895-3.015C456.563,421.337,450.961,421.22,445.362,421.22z
                        M497.979,421.126c-5.739,0-11.48,0.083-17.216-0.046c-2.001-0.046-2.553,0.584-2.492,2.532c0.135,4.368,0.13,8.746,0,13.116
                        c-0.054,1.806,0.64,3.107,1.722,4.402c9.158,10.964,26.927,10.951,36.036-0.04c0.971-1.169,1.737-2.314,1.697-3.997
                        c-0.112-4.505-0.139-9.021,0.008-13.525c0.064-2.012-0.604-2.525-2.54-2.485C509.458,421.202,503.717,421.126,497.979,421.126z
                        M550.571,421.22c-5.463,0-10.929,0.111-16.386-0.051c-2.387-0.07-3.508,0.388-3.253,3.084c0.255,2.708,0.411,5.514-0.013,8.177
                        c-1.126,7.083,2.929,10.871,8.304,13.94c5.229,2.986,10.903,3.525,16.655,2.327c9.814-2.046,14.296-7.699,14.296-17.508
                        c0-1.912-0.195-3.849,0.043-5.729c0.416-3.26-0.474-4.58-4.087-4.33C560.967,421.485,555.76,421.22,550.571,421.22z
                        M603.173,421.126c-5.739,0-11.48,0.077-17.216-0.043c-1.952-0.041-2.59,0.5-2.526,2.497c0.143,4.505,0.122,9.019,0.005,13.525
                        c-0.043,1.69,0.74,2.829,1.705,3.993c9.119,10.988,26.895,10.996,36.027,0.018c0.969-1.162,1.761-2.299,1.721-3.988
                        c-0.113-4.507-0.133-9.02,0.004-13.525c0.059-1.963-0.52-2.565-2.506-2.521C614.654,421.208,608.912,421.126,603.173,421.126z
                        M655.74,421.22c-5.466,0-10.934,0.111-16.395-0.051c-2.39-0.071-3.507,0.391-3.253,3.087c0.257,2.708,0.4,5.512-0.011,8.181
                        c-1.116,7.271,3.057,11.089,8.641,14.149c5.3,2.903,10.996,3.326,16.706,2.037c9.72-2.194,13.906-7.65,13.906-17.411
                        c0-2.185-0.207-4.397,0.052-6.551c0.365-3.028-0.913-3.614-3.664-3.503C666.401,421.372,661.068,421.22,655.74,421.22z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M446.051,407.904c-5.339,0-10.682-0.07-16.018,0.032c-2.221,0.043-3.31-0.007-2.866-2.985c2.978-20.016,5.727-40.066,8.468-60.116
                        c0.285-2.087,1.139-2.669,3.172-2.647c8.897,0.099,17.797,0.108,26.695-0.002c2.229-0.028,3.093,0.444,2.964,2.897
                        c-1.049,19.956-2.024,39.917-2.888,59.881c-0.114,2.651-1.205,3.057-3.511,2.99C456.733,407.796,451.39,407.904,446.051,407.904z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10" d="
                        M569.162,407.9c-12.277,0-24.442-0.028-36.602,0.021c-2.857,0.01-2.009-2.18-2.083-3.51c-0.729-13.256-1.332-26.52-1.969-39.782
                        c-0.314-6.563-0.545-13.13-0.971-19.685c-0.145-2.186,0.516-2.788,2.688-2.761c9.036,0.114,18.071,0.114,27.105,0
                        c2.169-0.027,2.776,0.834,3.045,2.792C563.245,365.83,566.187,386.674,569.162,407.9z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M672.532,407.904c-12.016,0-23.646-0.048-35.279,0.044c-2.034,0.016-2.734-0.662-3.194-2.62
                        c-4.729-20.077-9.554-40.13-14.413-60.177c-0.57-2.344,0.013-3.023,2.471-2.975c7.801,0.149,15.605,0.072,23.409,0.048
                        c3.585-0.011,5.964,1.589,7.102,5.021c6.542,19.698,13.094,39.395,19.632,59.095C672.386,406.715,672.4,407.127,672.532,407.904z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M376.918,342.231c-2.178,9.214-4.27,18.089-6.373,26.959c-2.77,11.682-5.652,23.337-8.251,35.057
                        c-0.66,2.978-1.915,3.779-4.876,3.731c-11.203-0.186-22.412-0.074-33.676-0.074c-0.59-1.328,0.179-2.384,0.533-3.452
                        c6.144-18.506,12.313-37.004,18.482-55.502c1.868-5.598,3.38-6.709,9.166-6.716C360.112,342.223,368.304,342.231,376.918,342.231z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10" d="
                        M340.256,421.22c5.611,0,11.223,0.083,16.832-0.042c2.077-0.048,2.995,0.438,2.806,2.697c-0.192,2.311-0.041,4.649-0.041,6.976
                        c0,10.117-4.122,15.528-14.024,17.773c-5.742,1.301-11.401,0.74-16.716-2.13c-5.658-3.055-9.796-6.905-8.576-14.17
                        c0.335-1.998,0.279-4.129,0.002-6.144c-0.548-3.979,0.619-5.544,4.95-5.141c4.884,0.455,9.841,0.104,14.767,0.104
                        C340.256,421.168,340.256,421.194,340.256,421.22z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M445.362,421.22c5.599,0,11.2,0.117,16.794-0.054c2.398-0.074,3.066,0.76,2.895,3.015c-0.174,2.309-0.038,4.64-0.038,6.961
                        c0,9.853-4.419,15.462-14.251,17.542c-5.748,1.217-11.435,0.702-16.655-2.3c-5.335-3.067-9.515-6.773-8.354-13.908
                        c0.454-2.794,0.217-5.733,0.029-8.589c-0.146-2.227,0.685-2.761,2.785-2.711C434.163,421.307,439.763,421.22,445.362,421.22z" />
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                                stroke-miterlimit="10"
                                d="
                        M550.571,421.22c5.188,0,10.396,0.265,15.56-0.09c3.613-0.25,4.503,1.07,4.087,4.33c-0.238,1.88-0.043,3.817-0.043,5.729
                        c0,9.809-4.481,15.461-14.296,17.508c-5.752,1.199-11.426,0.659-16.655-2.327c-5.375-3.069-9.43-6.857-8.304-13.94
                        c0.424-2.664,0.268-5.47,0.013-8.177c-0.255-2.696,0.866-3.154,3.253-3.084C539.643,421.331,545.108,421.22,550.571,421.22z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10"
                            d="
                        M655.74,421.22c5.328,0,10.661,0.152,15.982-0.062c2.751-0.111,4.029,0.475,3.664,3.503c-0.259,2.153-0.052,4.366-0.052,6.551
                        c0,9.761-4.187,15.217-13.906,17.411c-5.71,1.289-11.406,0.867-16.706-2.037c-5.584-3.06-9.757-6.878-8.641-14.149
                        c0.411-2.669,0.268-5.473,0.011-8.181c-0.254-2.697,0.863-3.158,3.253-3.087C644.807,421.331,650.274,421.22,655.74,421.22z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10" d="
                        M649.164,565.692c0,27.66,0.003,55.32-0.005,82.978c-0.001,6.994-2.038,9.027-9.034,9.027c-55.318,0.006-110.639,0.006-165.957,0
                        c-6.994,0-9.021-2.029-9.021-9.034c-0.004-55.318-0.004-110.639,0-165.957c0-7.006,2.017-9.021,9.029-9.021
                        c55.319-0.003,110.639-0.003,165.955,0c7.002,0,9.028,2.028,9.028,9.028C649.167,510.372,649.164,538.033,649.164,565.692z
                        M478.417,565.698c0,24.912,0.049,49.822-0.065,74.732c-0.014,3.025,0.507,4.094,3.893,4.087
                        c49.958-0.113,99.917-0.082,149.875,0.016c2.851,0.006,3.913-0.373,3.903-3.658c-0.144-50.097-0.155-100.191-0.048-150.286
                        c0.007-3.216-0.946-3.705-3.868-3.7c-49.958,0.092-99.918,0.107-149.875-0.024c-3.414-0.009-3.892,1.103-3.879,4.101
                        C478.465,515.877,478.417,540.789,478.417,565.698z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10" d="
                        M396.126,480.256c13.692,0,27.385-0.018,41.078,0.007c5.894,0.012,8.182,2.302,8.2,8.201c0.032,9.859,0.032,19.717,0,29.576
                        c-0.018,5.891-2.313,8.196-8.207,8.2c-27.386,0.019-54.771,0.019-82.157,0c-5.891-0.004-8.175-2.309-8.193-8.209
                        c-0.029-9.858-0.029-19.716,0-29.576c0.019-5.903,2.293-8.18,8.202-8.192C368.741,480.239,382.433,480.256,396.126,480.256z
                        M396.053,512.977c0,0.017,0,0.03,0,0.045c11.076,0,22.152-0.063,33.226,0.051c2.286,0.026,3.056-0.551,2.959-2.914
                        c-0.196-4.778-0.158-9.568-0.059-14.352c0.036-1.785-0.491-2.32-2.298-2.315c-22.425,0.058-44.85,0.06-67.274-0.003
                        c-1.936-0.006-2.602,0.482-2.541,2.492c0.142,4.645,0.218,9.306-0.022,13.941c-0.136,2.627,0.731,3.147,3.195,3.114
                        C374.175,512.899,385.115,512.977,396.053,512.977z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10" d="
                        M419.323,611.731c0.005,2.321,0.129,4.65-0.022,6.963c-0.247,3.742-3.051,6.322-6.679,6.354c-3.64,0.036-6.637-2.515-6.786-6.218
                        c-0.193-4.772-0.2-9.564,0.004-14.336c0.156-3.68,3.208-6.211,6.851-6.146c3.46,0.062,6.299,2.521,6.588,6.012
                        C419.481,606.801,419.319,609.272,419.323,611.731z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10" d="
                        M563.673,585.498c-10.406,0-20.812-0.018-31.22,0.009c-4.957,0.015-7.907,2.361-8.041,6.276c-0.138,4.056,2.951,6.668,8.059,6.675
                        c20.947,0.018,41.899-0.01,62.849,0.018c5.77,0.009,9.268,4.141,7.561,8.821c-0.479,1.305-1.15,2.84-2.446,3.152
                        c-4.062,0.983-3.878,3.838-3.813,7.042c0.096,4.479-2.567,7.331-6.444,7.425c-4.059,0.097-6.702-2.813-6.821-7.508
                        c-0.142-5.617-0.142-5.617-5.9-5.617c-9.993,0-19.992,0.078-29.984-0.056c-2.538-0.034-3.516,0.437-3.452,3.273
                        c0.15,6.854-1.935,9.788-6.403,9.907c-4.634,0.122-6.857-2.954-6.78-9.952c0.024-2.334-0.458-3.063-2.979-3.456
                        c-8.741-1.352-15.073-7.913-16.569-16.5c-1.345-7.727,2.493-15.812,9.745-20.163c1.521-0.914,2.386-1.524,2.063-3.663
                        c-2.458-16.222-4.767-32.464-6.974-48.719c-0.281-2.052-0.791-2.864-2.938-2.731c-2.864,0.175-5.752,0.142-8.621,0.008
                        c-4.043-0.19-6.748-3.07-6.63-6.845c0.111-3.612,2.746-6.309,6.644-6.404c5.609-0.133,11.228-0.07,16.841-0.021
                        c3.884,0.034,6.007,2.299,6.816,5.882c0.448,1.993,0.613,4.055,0.905,6.083c1.097,7.733,1.096,7.733,8.71,7.733
                        c23.277,0,46.557-0.009,69.832,0.003c7.331,0.006,9.905,3.385,7.897,10.459c-4.024,14.176-8.087,28.343-12.179,42.5
                        c-1.478,5.114-3.188,6.356-8.506,6.363C584.487,585.514,574.081,585.498,563.673,585.498z M531.94,539.417
                        c1.482,10.415,2.993,20.527,4.319,30.659c0.278,2.119,1.323,2.206,2.995,2.201c16.565-0.047,33.131-0.065,49.698,0.021
                        c1.97,0.01,2.903-0.548,3.427-2.456c2.528-9.213,5.046-18.436,7.841-27.568c0.82-2.677,0.026-2.903-2.271-2.892
                        c-15.197,0.066-30.395,0.034-45.591,0.034C545.663,539.417,538.968,539.417,531.94,539.417z" />
                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="5"
                            stroke-miterlimit="10" d="
                        M531.94,539.417c7.027,0,13.723,0,20.419,0c15.196,0,30.394,0.032,45.591-0.034c2.297-0.012,3.091,0.215,2.271,2.892
                        c-2.795,9.133-5.313,18.355-7.841,27.568c-0.523,1.908-1.457,2.466-3.427,2.456c-16.567-0.087-33.133-0.068-49.698-0.021
                        c-1.672,0.005-2.717-0.082-2.995-2.201C534.934,559.944,533.423,549.832,531.94,539.417z" />
                    </g>
                </svg>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 stroke-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">
                                    Products list successfully imported.
                                </h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Please verify our website.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                        <div class="flex items-center gap-4">
                            <a href="https://github.com/sponsors/taylorotwell" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="-mt-px mr-1 w-5 h-5 stroke-gray-400 dark:stroke-gray-600 group-hover:stroke-gray-600 dark:group-hover:stroke-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                Powered by MercaTodo - Web Page
                            </a>
                        </div>
                    </div>

                    <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                        Do not print this email, save the planet.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
