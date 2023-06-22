<script setup>
const props = defineProps({
    order: Object,
});
</script>
<template>
    <div
        v-if="order.payment_status == 'canceled'"
        class="rounded-md px-2 py-2 bg-red-200 text-center font-bold"
    >
        CANCELADO
    </div>
    <div
        v-if="order.payment_status == 'paid'"
        class="rounded-md px-2 py-2 bg-green-200 text-center font-bold"
    >
        PAGADO
    </div>
    <div
        v-if="order.payment_status == 'waiting'"
        class="rounded-md px-2 py-2 bg-purple-300 text-center font-bold"
    >
        PAGO POR CONFIRMAR
    </div>
    <div
        v-if="order.url && (order.payment_status == 'pending')"
        class="rounded-md px-2 py-2 bg-yellow-200 text-center font-bold"
    >
        PENDIENTE
    </div>
    <div
        v-if="order.payment_status == 'verify_bank'"
        class="rounded-md px-2 py-2 bg-orange-300 text-center font-bold"
    >
        VERIFICAR CON SU BANCO
    </div>
    <div
        v-if="order.payment_status == 'approved_partial'"
        class="rounded-md px-2 py-2 bg-amber-300 text-center font-bold"
    >
        PAGO INCOMPLETO
    </div>
    <div
        v-if="order.payment_status == 'partial_expired'"
        class="rounded-md px-2 py-2 bg-zinc-300 text-center font-bold"
    >
        PAGO EXPIRADO
    </div>
    <div
        v-if="!order.url"
        class="rounded-md px-2 py-2 bg-blue-200 text-center font-bold"
    >
        SIN LINK DE PAGO
    </div>
</template>
