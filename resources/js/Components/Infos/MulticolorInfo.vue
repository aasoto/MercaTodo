<script setup>
const props = defineProps({
    color: String,
});

let bgColor = 'bg-teal-200';

switch (props.color) {
    case 'red':
        bgColor = 'bg-red-200';
        break;
    case 'green':
        bgColor = 'bg-green-200';
        break;
    case 'purple':
        bgColor = 'bg-purple-300';
        break;
    case 'yellow':
        bgColor = 'bg-yellow-200';
        break;
    case 'orange':
        bgColor = 'bg-orange-300';
        break;
    case 'amber':
        bgColor = 'bg-amber-300';
        break;
    case 'zinc':
        bgColor = 'bg-zinc-300';
        break;
    case 'blue':
        bgColor = 'bg-blue-200';
        break;
}
</script>
<template>
    <div :class="`rounded-md px-2 py-2 ${bgColor} text-center font-bold`">
        <slot />
    </div>
</template>
