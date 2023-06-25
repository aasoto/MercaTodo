<script setup>
import MulticolorInfo from '@/Components/Infos/MulticolorInfo.vue';

const props = defineProps({
    order: Object,
});
</script>
<template>
    <MulticolorInfo color="red" v-if="order.payment_status == 'canceled'">
        CANCELADO
    </MulticolorInfo>
    <MulticolorInfo color="green" v-if="order.payment_status == 'paid'">
        PAGADO
    </MulticolorInfo>
    <MulticolorInfo color="purple" v-if="order.payment_status == 'waiting'">
        PAGO POR CONFIRMAR
    </MulticolorInfo>
    <MulticolorInfo color="yellow" v-if="order.url && (order.payment_status == 'pending')">
        PENDIENTE
    </MulticolorInfo>
    <MulticolorInfo color="orange" v-if="order.payment_status == 'verify_bank'">
        VERIFICAR CON SU BANCO
    </MulticolorInfo>
    <MulticolorInfo color="amber" v-if="order.payment_status == 'approved_partial'">
        PAGO INCOMPLETO
    </MulticolorInfo>
    <MulticolorInfo color="zinc" v-if="order.payment_status == 'partial_expired'">
        PAGO EXPIRADO
    </MulticolorInfo>
    <MulticolorInfo color="blue" v-if="!order.url">
        SIN LINK DE PAGO
    </MulticolorInfo>
</template>
